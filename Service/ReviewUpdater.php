<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\Comment;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\Product;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\ProductReview;
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
    const URL = 'https://cl.avis-verifies.com/fr/cache/%s/AWS/PRODUCT_API/REVIEWS/%s.json';

    /**
     * @var ReviewRepository
     */
    private $reviewRepository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var string
     */
    private $websiteId;

    /**
     * @var \Doctrine\ORM\Query
     */
    private $nextProductQuery;

    /**
     * @var \Doctrine\ORM\Query
     */
    private $findProductReviewByIdQuery;


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
        string $websiteId
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->manager = $manager;
        $this->websiteId = $websiteId;
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

        $client = new Client();

        $path = implode('/', str_split(substr($this->websiteId, 0, 3))) . '/' . $this->websiteId;

        $count = 0;
        while (null !== $product = $this->findNextProduct()) {
            $url = sprintf(static::URL, $path, $product->getProduct()->getReference());

            try {
                $res = $client->request('GET', $url);
            } catch (GuzzleException $e) {
                continue;
            }

            // Abort if request did not succeed
            if (!in_array($res->getStatusCode(), [200, 304])) {
                continue;
            }

            $data = json_decode($res->getBody(), true);

            if (empty($data)) {
                continue;
            }

            foreach ($data as $datum) {
                $productReview = $this->findProductReviewById($datum['id_review_product']);
                if ($productReview) {
                    // TODO Watch for updates

                    continue;
                }

                $review = $this->reviewRepository->findOneByReviewId($datum['id_review']);
                if (!$review) {
                    /** @var \Ekyna\Bundle\VerifiedReviewsBundle\Entity\Review $review */
                    $review = $this->reviewRepository->createNew();
                    $review
                        ->setReviewId($datum['id_review'])
                        ->setEmail($datum['email'])
                        ->setLastName($datum['lastname'])
                        ->setFirstName($datum['firstname'])
                        ->setDate(new \DateTime($datum['review_date']))
                        ->setContent($datum['review'])
                        ->setRate($datum['rate'])
                        ->setOrderNumber($datum['order_ref']);

                    if (isset($datum['moderation']) && !empty($datum['moderation'])) {
                        foreach ($datum['moderation'] as $moderation) {
                            // TODO Watch for updates
                            $comment = new Comment();
                            $comment
                                ->setDate($this->parseCommentDate($moderation['comment_date']))
                                ->setCustomer($moderation['comment_origin'] === '3')
                                ->setMessage($moderation['comment']);

                            $review->addComment($comment);
                        }
                    }
                }

                $productReview = new ProductReview();
                $productReview
                    ->setProductReviewId($datum['id_review_product'])
                    ->setProduct($product)
                    ->setReview($review);

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
     * Parse the comment date.
     *
     * @param string $input
     *
     * @return \DateTime
     */
    private function parseCommentDate(string $input)
    {
        try {
            return new \DateTime($input);
        } catch (\Exception $e) {
        }

        if (!preg_match('~^(?P<date>[0-9\-]+)T(?P<time>[0-9\:]{8})\.0000000 (?P<zone>[0-9\-\+\:]+)$~', $input, $matches)) {
            throw new \Exception("Failed to parse date time string.");
        }

        $date = "{$matches['date']}T{$matches['time']}" . ($matches['zone'][0] === '-' ? '' : '+') . $matches['zone'];

        if (!$date = \DateTime::createFromFormat(\DateTime::ATOM, $date)) {
            throw new \Exception("Failed to parse date time string.");
        }

        return $date;
    }

    /**
     * Finds the product to fetch.
     *
     * @return Product|null
     */
    private function findNextProduct()
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
            ->setParameter('yesterday', $yesterday, \Doctrine\DBAL\Types\Type::DATETIME)
            ->getOneOrNullResult();
    }

    /**
     * Finds the product review by its id.
     *
     * @param string $id
     *
     * @return ProductReview|null
     */
    private function findProductReviewById($id)
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
}
