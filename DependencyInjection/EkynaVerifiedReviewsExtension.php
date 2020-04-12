<?php

namespace Ekyna\Bundle\VerifiedReviewsBundle\DependencyInjection;

use Ekyna\Bundle\ResourceBundle\DependencyInjection\AbstractExtension;
use Ekyna\Bundle\VerifiedReviewsBundle\Service;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class EkynaVerifiedReviewsExtension
 * @package Ekyna\Bundle\VerifiedReviewsBundle\DependencyInjection
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class EkynaVerifiedReviewsExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->configure($configs, 'ekyna_verified_reviews', new Configuration(), $container);

        $definition = $container->getDefinition(Service\ProductUpdater::class);
        $definition->setArgument(3, $config['credential']['website_id']);

        $definition = $container->getDefinition(Service\ReviewUpdater::class);
        $definition->setArgument(3, $config['credential']['website_id']);

        $definition = $container->getDefinition(Service\OrderNotifier::class);
        $definition->setArgument(5, [
            'enable'       => $config['notification']['enable'],
            'delay'        => $config['notification']['delay'],
            'report_email' => $config['notification']['report_email'],
            'limit'        => $config['notification']['limit'],
            'website_id'   => $config['credential']['website_id'],
            'secret_key'   => $config['credential']['secret_key'],
            'debug'        => $container->getParameter('kernel.debug'),
        ]);

        $definition = $container->getDefinition(Service\Renderer\ReviewRenderer::class);
        $definition->setArgument(5, $config['layout']);
    }
}
