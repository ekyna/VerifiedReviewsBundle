<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Ekyna\Bundle\ProductBundle\Model\ProductInterface;
use Ekyna\Bundle\VerifiedReviewsBundle\Entity\Product;

/**
 * Class ProductRepository
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Repository
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ProductRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param ProductInterface $product
     *
     * @return Product|null
     */
    public function findOneByProduct(ProductInterface $product): ?Product
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->findOneBy(['product' => $product]);
    }
}
