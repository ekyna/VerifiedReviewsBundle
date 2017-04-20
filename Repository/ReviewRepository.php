<?php

declare(strict_types=1);

namespace Ekyna\Bundle\VerifiedReviewsBundle\Repository;

use Doctrine\ORM\Query;
use Ekyna\Bundle\ProductBundle\Model\ProductInterface;
use Ekyna\Bundle\VerifiedReviewsBundle\Model\ReviewInterface;
use Ekyna\Component\Resource\Doctrine\ORM\Repository\ResourceRepository;

/**
 * Class ReviewRepository
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Repository
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ReviewRepository extends ResourceRepository implements ReviewRepositoryInterface
{
    private ?Query $findByProductQuery = null;

    public function findOneByReviewId(string $id): ?ReviewInterface
    {
        return $this->findOneBy([
            'reviewId' => $id,
        ]);
    }

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
