<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Repository;

use Ekyna\Bundle\ProductBundle\Model\ProductInterface;
use Ekyna\Bundle\VerifiedReviewsBundle\Model\ReviewInterface;
use Ekyna\Component\Resource\Doctrine\ORM\ResourceRepository;

/**
 * Class ReviewRepository
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Repository
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ReviewRepository extends ResourceRepository
{
    /**
     * @var \Doctrine\ORM\Query
     */
    private $findByProductQuery;


    /**
     * Finds one review by its review id (id_review_product).
     *
     * @param string $id
     *
     * @return ReviewInterface|null
     */
    public function findOneByReviewId(string $id): ?ReviewInterface
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->findOneBy([
            'reviewId' => $id,
        ]);
    }

    /**
     * Finds reviews by product.
     *
     * @param ProductInterface $product
     * @param int              $limit
     * @param int              $offset
     *
     * @return ReviewInterface[]
     */
    public function findByProduct(ProductInterface $product, int $limit = 16, int $offset = 0): array
    {
        if (!$this->findByProductQuery) {
            $qb = $this->createQueryBuilder('r');

            $this->findByProductQuery = $qb
                ->join('r.product', 'p')
                ->andWhere($qb->expr()->eq('p.product', ':product'))
                ->orderBy('r.date', 'DESC')
                ->getQuery();
        }

        return $this->findByProductQuery
            ->setParameter('product', $product)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getResult();
    }
}
