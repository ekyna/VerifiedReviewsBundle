<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Service;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\Comment;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\Product;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\ProductReview;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\Review;
use Ekyna\Bundle\VerifiedReviewsBundle\Repository\ReviewRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

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
     * @var \Doctrine\ORM\Query
     */
    protected $findProductReviewByIdQuery;

    /**
     * @var Client
     */
    protected $client;


    /**
     * Constructor.
     *
     * @param ReviewRepository       $reviewRepository
     * @param EntityManagerInterface $manager
     * @param string                 $websiteId
     */
    public function __construct(
        ReviewRepository $reviewRepository,
        EntityManagerInterface $manager,
        string $websiteId = null
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->manager          = $manager;
        $this->websiteId        = $websiteId;
    }

    /**
     * Updates the review product list.
     *
     * @return bool Whether it succeed.
     */
    public function updateReviews()
    {
        if (empty($this->websiteId)) {
            return false;
        }

        $directory    = implode('/', str_split(substr($this->websiteId, 0, 3))) . '/' . $this->websiteId;
        $this->client = new Client([
            'base_uri' => sprintf(static::URL, $directory),
        ]);

        $count = 0;
        while (null !== $product = $this->findNextProduct()) {
            if (empty($data = $this->loadReviewsJson($product))) {
                continue;
            }

            foreach ($data as $datum) {
                $productReview = $this->findProductReviewById($datum['id_review_product']);
                if (!$productReview) {
                    // If review exists
                    $review = $this->reviewRepository->findOneByReviewId($datum['id_review']);
                    if (!$review) {
                        /** @var \Ekyna\Bundle\VerifiedReviewsBundle\Entity\Review $review */
                        $review = $this->reviewRepository->createNew();
                    }

                    $this->updateReview($review, $datum);

                    // Creates the ProductReview
                    $productReview = new ProductReview();
                    $productReview
                        ->setProductReviewId($datum['id_review_product'])
                        ->setProduct($product)
                        ->setReview($review);
                } else {
                    $review = $productReview->getReview();

                    if (!$this->updateReview($review, $datum)) {
                        continue;
                    }
                }

                $this->manager->persist($review);

                $count++;

                if ($count % 20 === 0) {
                    $this->manager->flush();
                }
            }

            $product->setFetchedAt(new \DateTime());
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

        if ($review->getReviewId() != $data['id_review']) {
            $review->setReviewId($data['id_review']);
            $changed = true;
        }
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
        if ($review->getDate() != $date = new \DateTime($data['review_date'])) {
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
                $date       = $this->parseCommentDate($moderation['comment_date']);
                $isCustomer = $moderation['comment_origin'] === '3';
                $message    = trim($moderation['comment']);

                // Existing comment lookup
                foreach ($review->getComments() as $c) {
                    if ($c->getDate()->format('Y-m-d H:i:s') != $date->format('Y-m-d H:i:s')) {
                        continue;
                    }

                    if ($c->isCustomer() === $isCustomer) {
                        continue;
                    }

                    if ($c->getMessage() != $message) {
                        $c->setMessage($message);
                        $changed = true;
                    }

                    continue 2;
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

        return empty($string) ? $string : null;
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
        $file = $product->getProduct()->getReference() . '.json';

        try {
            $res = $this->client->request('GET', $file);
        } catch (GuzzleException $e) {
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
     * @return \DateTime
     */
    protected function parseCommentDate(string $input)
    {
        try {
            return new \DateTime($input);
        } catch (\Exception $e) {
        }

        $regex = '~^(?P<date>[0-9\-]+)T(?P<time>[0-9\:]{8})\.0000000 (?P<zone>[0-9\-\+\:]+)$~';
        if (!preg_match($regex, $input, $matches)) {
            throw new \Exception("Failed to parse date time string.");
        }

        /*$date = "{$matches['date']}T{$matches['time']}" . ($matches['zone'][0] === '-' ? '' : '+') . $matches['zone'];
        if (!$date = \DateTime::createFromFormat(\DateTime::ATOM, $date)) {
            throw new \Exception("Failed to parse date time string.");
        }*/

        return new \DateTime("{$matches['date']} {$matches['time']}");
    }

    /**
     * Finds the product to fetch.
     *
     * @return Product|null
     */
    protected function findNextProduct()
    {
        if (!$this->nextProductQuery) {
            $ex = new Expr();

            $this->nextProductQuery = $this->manager
                ->createQueryBuilder()
                ->from(Product::class, 'p')
                ->select('p')
                ->where($ex->orX(
                    $ex->isNull('p.fetchedAt'),
                    $ex->lte('p.fetchedAt', ':yesterday')
                ))
                ->setMaxResults(1)
                ->getQuery()
                ->useQueryCache(true);
        }

        $yesterday = new \DateTime('-1 day');

        return $this
            ->nextProductQuery
            ->setParameter('yesterday', $yesterday, Types::DATETIME_MUTABLE)
            ->getOneOrNullResult();
    }

    /**
     * Finds the product review by its id.
     *
     * @param string $id
     *
     * @return ProductReview|null
     */
    protected function findProductReviewById($id)
    {
        if (!$this->findProductReviewByIdQuery) {
            $ex = new Expr();

            $this->findProductReviewByIdQuery = $this->manager
                ->createQueryBuilder()
                ->from(ProductReview::class, 'pr')
                ->select('pr')
                ->where($ex->eq('pr.productReviewId', ':id'))
                ->setMaxResults(1)
                ->getQuery()
                ->useQueryCache(true);
        }

        return $this
            ->findProductReviewByIdQuery
            ->setParameter('id', $id)
            ->getOneOrNullResult();
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
