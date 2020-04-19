<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Service;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\Comment;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\Product;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\Review;
use Ekyna\Bundle\VerifiedReviewsBundle\Repository\ReviewRepository;
use GuzzleHttp\Client;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ReviewUpdater
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Service
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ReviewUpdater
{
    const URL = 'https://cl.avis-verifies.com/fr/cache/%s/AWS/PRODUCT_API/REVIEWS/';

    /**
     * @var ReviewRepository
     */
    protected $reviewRepository;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @var string
     */
    protected $websiteId;

    /**
     * @var \Doctrine\ORM\Query
     */
    protected $nextProductQuery;

    /**
     * @var Client
     */
    protected $client;


    /**
     * Constructor.
     *
     * @param ReviewRepository       $reviewRepository
     * @param ValidatorInterface     $validator
     * @param EntityManagerInterface $manager
     * @param string                 $websiteId
     */
    public function __construct(
        ReviewRepository $reviewRepository,
        ValidatorInterface $validator,
        EntityManagerInterface $manager,
        string $websiteId = null
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->validator = $validator;
        $this->manager = $manager;
        $this->websiteId = $websiteId;
    }

    /**
     * Updates the review product list.
     *
     * @param bool $full Whether to fetch all reviews (default is to stop when a review is older that 30 days, by product)
     *
     * @return bool Whether it succeed.
     */
    public function updateReviews(bool $full = false)
    {
        if (empty($this->websiteId)) {
            return false;
        }

        if ($full) {
            $this->clearFetchedAt();
        }

        $directory = implode('/', str_split(substr($this->websiteId, 0, 3))) . '/' . $this->websiteId;
        $this->client = new Client([
            'base_uri' => sprintf(static::URL, $directory),
        ]);

        $count = 0;
        while (null !== $product = $this->findNextProduct()) {
            if (!empty($data = $this->loadReviewsJson($product))) {
                foreach ($data as $datum) {
                    if (!$full) {
                        $diff = (new DateTime($datum['review_date']))->diff(new DateTime(), true)->days;
                        if ($diff > 30) {
                            break;
                        }
                    }

                    $reviewId = $datum['id_review_product'];

                    $review = $this
                        ->reviewRepository
                        ->findOneByReviewId($reviewId);

                    if (!$review) {
                        /** @var Review $review */
                        $review = $this->reviewRepository->createNew();
                        $review
                            ->setReviewId($reviewId)
                            ->setProduct($product);
                    }

                    $this->updateReview($review, $datum);

                    if (0 < $this->validator->validate($review)->count()) {
                        continue;
                    }

                    $this->manager->persist($review);

                    $count++;

                    if ($count % 20 === 0) {
                        $this->manager->flush();
                    }
                }
            }

            $product->setFetchedAt(new DateTime());
            $this->manager->persist($product);
            $this->manager->flush();

            $this->manager->clear();
        }

        // TODO Watch for deletions

        return true;
    }

    /**
     * Updates the review.
     *
     * @param Review $review
     * @param array  $data
     *
     * @return bool Whether the review has been updated.
     */
    protected function updateReview(Review $review, array $data)
    {
        $changed = false;

        if ($review->getEmail() != $datum = $this->sanitizeString($data['email'])) {
            $review->setEmail($datum);
            $changed = true;
        }
        if ($review->getLastName() != $datum = $this->sanitizeString($data['lastname'])) {
            $review->setLastName($datum);
            $changed = true;
        }
        if ($review->getFirstName() != $datum = $this->sanitizeString($data['firstname'])) {
            $review->setFirstName($datum);
            $changed = true;
        }
        if ($review->getDate() != $date = new DateTime($data['review_date'])) {
            $review->setDate($date);
            $changed = true;
        }
        if ($review->getContent() != $datum = $this->remove4BytesChars(trim($data['review']))) {
            $review->setContent($datum);
            $changed = true;
        }
        if ($review->getRate() != $data['rate']) {
            $review->setRate($data['rate']);
            $changed = true;
        }
        if ($review->getOrderNumber() != $data['order_ref']) {
            $review->setOrderNumber($data['order_ref']);
            $changed = true;
        }

        if (isset($data['moderation']) && !empty($data['moderation'])) {
            foreach ($data['moderation'] as $moderation) {
                $date = $this->parseCommentDate($moderation['comment_date']);
                $isCustomer = $moderation['comment_origin'] === '3';
                $message = trim($moderation['comment']);

                // Existing comment lookup
                foreach ($review->getComments() as $c) {
                    $sameDate = $c->getDate()->format('Y-m-d H:i:s') === $date->format('Y-m-d H:i:s');
                    $sameOrigin = $c->isCustomer() === $isCustomer;
                    if ($sameDate && $sameOrigin) {
                        // Comment found
                        // Update message if needed
                        if ($c->getMessage() != $message) {
                            $c->setMessage($message);
                            $changed = true;
                        }

                        // Next feed comment
                        continue 2;
                    }
                }

                $comment = new Comment();
                $comment
                    ->setDate($date)
                    ->setCustomer($isCustomer)
                    ->setMessage($message);

                $review->addComment($comment);

                $changed = true;
            }
        }

        return $changed;
    }

    /**
     * Sanitizes the given string.
     *
     * @param string|null $string
     *
     * @return null|string
     */
    protected function sanitizeString(string $string = null)
    {
        $string = trim($string);

        return !empty($string) ? $string : null;
    }

    /**
     * Fetch the product reviews JSON data.
     *
     * @param Product $product
     *
     * @return array|null
     */
    protected function loadReviewsJson(Product $product)
    {
        $file = urlencode($product->getProduct()->getReference()) . '.json';

        try {
            $res = $this->client->request('GET', $file);
        } catch (\Throwable $e) {
            return null;
        }

        // Abort if request did not succeed
        if (!in_array($res->getStatusCode(), [200, 304])) {
            return null;
        }

        return json_decode($res->getBody(), true);
    }

    /**
     * Parse the comment date.
     *
     * @param string $input
     *
     * @return DateTime
     */
    protected function parseCommentDate(string $input): DateTime
    {
        try {
            return new DateTime($input);
        } catch (\Exception $e) {
        }

        $regex = '~^(?P<date>[0-9\-]+)T(?P<time>[0-9\:]{8})\.0000000 (?P<zone>[0-9\-\+\:]+)$~';
        if (!preg_match($regex, $input, $matches)) {
            throw new \Exception("Failed to parse date time string.");
        }

        /*$date = "{$matches['date']}T{$matches['time']}" . ($matches['zone'][0] === '-' ? '' : '+') . $matches['zone'];
        if (!$date = DateTime::createFromFormat(DateTime::ATOM, $date)) {
            throw new \Exception("Failed to parse date time string.");
        }*/

        return new DateTime("{$matches['date']} {$matches['time']}");
    }

    /**
     * Finds the product to fetch.
     *
     * @return Product|null
     */
    protected function findNextProduct(): ?Product
    {
        if (!$this->nextProductQuery) {
            $ex = new Expr();

            $this->nextProductQuery = $this
                ->manager
                ->createQueryBuilder()
                ->from(Product::class, 'p')
                ->select('p')
                ->where($ex->orX(
                    $ex->isNull('p.fetchedAt'),
                    $ex->lte('p.fetchedAt', ':from_date')
                ))
                ->addOrderBy('p.fetchedAt', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->setParameter('from_date', new DateTime('-1 day'), Types::DATETIME_MUTABLE)
                ->useQueryCache(true);
        }

        return $this->nextProductQuery->getOneOrNullResult();
    }

    /**
     * Clears the products "fetched at" date.
     */
    protected function clearFetchedAt(): void
    {
        /** @noinspection SqlResolve */
        $this
            ->manager
            ->createQuery(sprintf(
                "UPDATE %s p SET p.fetchedAt=NULL",
                Product::class))
            ->execute();
    }

    /**
     * Removes UTF8 4 bytes characters.
     * (Such characters would need a utf8mb4 mysql charset and collation)
     *
     * @param string $string
     *
     * @return string
     */
    private function remove4BytesChars(string $string): string
    {
        // https://stackoverflow.com/a/16496799
        return preg_replace('%(?:
          \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
          | [\xF1-\xF3][\x80-\xBF]{3}        # planes 4-15
          | \xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
        )%xs', "", $string);
    }
}
