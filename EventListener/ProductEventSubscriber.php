<?php

declare(strict_types=1);

namespace Ekyna\Bundle\VerifiedReviewsBundle\EventListener;

use Ekyna\Bundle\CmsBundle\Event\SchemaOrgEvent;
use Ekyna\Bundle\ProductBundle\Event\ProductEvents;
use Ekyna\Bundle\ProductBundle\Model\ProductInterface;
use Ekyna\Bundle\VerifiedReviewsBundle\Repository\ProductRepository;
use InvalidArgumentException;
use Spatie\SchemaOrg\Product;
use Spatie\SchemaOrg\Schema;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ProductEventSubscriber
 * @package Ekyna\Bundle\VerifiedReviewsBundle\EventListener
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ProductEventSubscriber implements EventSubscriberInterface
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function onProductSchemaOrg(SchemaOrgEvent $event): void
    {
        $product = $event->getResource();
        if (!$product instanceof ProductInterface) {
            throw new InvalidArgumentException('Expected instance of ' . ProductInterface::class);
        }

        $schema = $event->getSchema();
        if (!$schema instanceof Product) {
            throw new InvalidArgumentException('Expected instance of ' . Product::class);
        }

        /** @var \Ekyna\Bundle\VerifiedReviewsBundle\Entity\Product $p */
        $p = $this->productRepository->findOneBy([
            'product' => $product,
        ]);
        if (is_null($p)) {
            return;
        }

        $schema->aggregateRating(
            Schema::aggregateRating()
                ->ratingValue($p->getRate())
                ->reviewCount($p->getNbReviews())
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductEvents::SCHEMA_ORG => ['onProductSchemaOrg', 0],
        ];
    }
}
