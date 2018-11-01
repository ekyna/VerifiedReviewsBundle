<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Repository;

use Doctrine\DBAL\Types\Type;
use Ekyna\Bundle\ProductBundle\Model\ProductInterface;
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
     * Finds one review by its review id.
     *
     * @param string $reviewId
     *
     * @return \Ekyna\Bundle\VerifiedReviewsBundle\Entity\Review|null
     */
    public function findOneByReviewId($reviewId)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->findOneBy([
            'reviewId' => $reviewId,
        ]);
    }

    /**
     * Finds reviews by product.
     *
     * @param ProductInterface $product
     * @param int              $limit
     * @param int              $offset
     *
     * @return \Ekyna\Bundle\VerifiedReviewsBundle\Entity\Review[]
     */
    public function findByProduct(ProductInterface $product, $limit = 16, $offset = 0)
    {
        if (!$this->findByProductQuery) {
            $qb = $this->createQueryBuilder('r');

            $this->findByProductQuery = $qb
                ->join('r.productReviews', 'pr')
                ->join('pr.product', 'p')
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
