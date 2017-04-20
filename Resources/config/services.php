<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Ekyna\Bundle\VerifiedReviewsBundle\Command\NotifyOrderCommand;
use Ekyna\Bundle\VerifiedReviewsBundle\Command\UpdateProductCommand;
use Ekyna\Bundle\VerifiedReviewsBundle\Command\UpdateReviewCommand;
use Ekyna\Bundle\VerifiedReviewsBundle\Controller\ApiController;
use Ekyna\Bundle\VerifiedReviewsBundle\EventListener\LoyaltyEventSubscriber;
use Ekyna\Bundle\VerifiedReviewsBundle\EventListener\ProductEventSubscriber;
use Ekyna\Bundle\VerifiedReviewsBundle\Repository\ProductRepository;
use Ekyna\Bundle\VerifiedReviewsBundle\Service\OrderNotifier;
use Ekyna\Bundle\VerifiedReviewsBundle\Service\ProductUpdater;
use Ekyna\Bundle\VerifiedReviewsBundle\Service\Renderer\ReviewRenderer;
use Ekyna\Bundle\VerifiedReviewsBundle\Service\ReviewUpdater;
use Ekyna\Bundle\VerifiedReviewsBundle\Service\Serializer\ReviewNormalizer;
use Ekyna\Bundle\VerifiedReviewsBundle\Twig\ReviewExtension;

return static function (ContainerConfigurator $container) {
    $container
        ->services()

        ->set('ekyna_verified_reviews.repository.product', ProductRepository::class)
            ->args([
                service('doctrine'),
            ])
            ->tag('doctrine.repository_service')

        ->set('ekyna_verified_reviews.controller.api', ApiController::class)
            ->args([
                service('ekyna_product.repository.product'),
                service('ekyna_verified_reviews.renderer.review'),
            ])
            ->alias(ApiController::class, 'ekyna_verified_reviews.controller.api')->public()

        ->set('ekyna_verified_reviews.updater.product', ProductUpdater::class)
            ->args([
                service('ekyna_product.repository.product'),
                service('validator'),
                service('doctrine.orm.default_entity_manager'),
                abstract_arg('website id'),
            ])

        ->set('ekyna_verified_reviews.updater.review', ReviewUpdater::class)
            ->args([
                service('ekyna_verified_reviews.repository.review'),
                service('ekyna_verified_reviews.factory.review'),
                service('validator'),
                service('doctrine.orm.default_entity_manager'),
                abstract_arg('website id'),
            ])

        ->set('ekyna_verified_reviews.notifier.order', OrderNotifier::class)
            ->args([
                service('doctrine.orm.default_entity_manager'),
                service('ekyna_commerce.helper.subject'),
                service('liip_imagine.cache.manager'),
                service('mailer'),
                param('ekyna_commerce.class.order'),
                abstract_arg('Order notifier configuration'),
            ])

        ->set('ekyna_verified_reviews.renderer.review', ReviewRenderer::class)
            ->args([
                service('ekyna_verified_reviews.repository.product'),
                service('ekyna_verified_reviews.repository.review'),
                service('serializer'),
                service('translator'),
                service('twig'),
                abstract_arg('Review renderer configuration'),
            ])
            ->tag('twig.runtime')

        ->set('ekyna_verified_reviews.normalizer.review', ReviewNormalizer::class)
            ->parent('ekyna_resource.normalizer.abstract')
            ->args([
                service('ekyna_commerce.factory.formatter'),
            ])
            ->tag('serializer.normalizer')
            ->tag('serializer.denormalizer')

        ->set('ekyna_verified_reviews.twig.review', ReviewExtension::class)
            ->args([
                service('ekyna_verified_reviews.renderer.review'),
            ])
            ->tag('twig.extension')

        ->set('ekyna_verified_reviews.listener.loyalty', LoyaltyEventSubscriber::class)
            ->args([
                service('ekyna_commerce.repository.order'),
                service('ekyna_commerce.features'),
                service('ekyna_commerce.updater.loyalty'),
            ])
            ->tag('resource.event_subscriber')

        ->set('ekyna_verified_reviews.listener.product', ProductEventSubscriber::class)
            ->args([
                service('ekyna_verified_reviews.repository.product'),
            ])
            ->tag('resource.event_subscriber')

        ->set('ekyna_verified_reviews.command.update_product', UpdateProductCommand::class)
            ->call('setProductUpdater', [
                service('ekyna_verified_reviews.updater.product'),
            ])
            ->tag('console.command')

        ->set('ekyna_verified_reviews.command.update_review', UpdateReviewCommand::class)
            ->call('setReviewUpdater', [
                service('ekyna_verified_reviews.updater.review'),
            ])
            ->tag('console.command')

        ->set('ekyna_verified_reviews.command.notify_order', NotifyOrderCommand::class)
            ->call('setOrderNotifier', [
                service('ekyna_verified_reviews.notifier.order'),
            ])
            ->tag('console.command')
    ;
};
