<?php

declare(strict_types=1);

namespace Ekyna\Bundle\VerifiedReviewsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ekyna\Bundle\ProductBundle\Model\ProductInterface;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\Product;

/**
 * Class ProductRepository
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Repository
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findOneByProduct(ProductInterface $product): ?Product
    {
        return $this->findOneBy(['product' => $product]);
    }
}
