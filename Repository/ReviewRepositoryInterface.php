<?php

declare(strict_types=1);

namespace Ekyna\Bundle\VerifiedReviewsBundle\Repository;

use Ekyna\Bundle\ProductBundle\Model\ProductInterface;
use Ekyna\Bundle\VerifiedReviewsBundle\Model\ReviewInterface;
use Ekyna\Component\Resource\Repository\ResourceRepositoryInterface;

/**
 * Interface ReviewRepositoryInterface
 * @package Ekyna\Bundle\VerifiedReviewsBundle\Repository
 * @author  Étienne Dauvergne <contact@ekyna.com>
 *
 * @implements ResourceRepositoryInterface<ReviewInterface>
 */
interface ReviewRepositoryInterface extends ResourceRepositoryInterface
{
    /**
     * Finds one review by its review id (id_review_product).
     */
    public function findOneByReviewId(string $id): ?ReviewInterface;

    /**
     * Finds reviews by product.
     *
     * @return array<ReviewInterface>
     */
    public function findByProduct(ProductInterface $product, int $limit = 16, int $offset = 0): array;
}
