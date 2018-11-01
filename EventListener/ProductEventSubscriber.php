<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\EventListener;

use Ekyna\Bundle\CmsBundle\Event\SchemaOrgEvent;
use Ekyna\Bundle\ProductBundle\Event\ProductEvents;
use Ekyna\Bundle\ProductBundle\Model\ProductInterface;
use Ekyna\Bundle\VerifiedReviewsBundle\Repository\ProductRepository;
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
    /**
     * @var ProductRepository
     */
    private $productRepository;


    /**
     * Constructor.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Product schema org handler.
     *
     * @param SchemaOrgEvent $event
     */
    public function onProductSchemaOrg(SchemaOrgEvent $event)
    {
        $product = $event->getResource();
        if (!$product instanceof ProductInterface) {
            throw new \InvalidArgumentException("Expected instance of " . ProductInterface::class);
        }

        $schema = $event->getSchema();
        if (!$schema instanceof Product) {
            throw new \InvalidArgumentException("Expected instance of " . Product::class);
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

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            ProductEvents::SCHEMA_ORG => ['onProductSchemaOrg', 0],
        ];
    }
}
