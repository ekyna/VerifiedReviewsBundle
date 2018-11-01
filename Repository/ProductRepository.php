<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Ekyna\Bundle\ProductBundle\Model\ProductInterface;

/**
 * Class ProductRepository
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Repository
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ProductRepository extends EntityRepository
{
    /**
     * @param ProductInterface $product
     *
     * @return \Ekyna\Bundle\VerifiedReviewsBundle\Entity\Product|null
     */
    public function findOneByProduct(ProductInterface $product)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->findOneBy(['product' => $product]);
    }
}
