<?php

declare(strict_types=1);

namespace Ekyna\Bundle\VerifiedReviewsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

/**
 * Class EkynaVerifiedReviewsExtension
 * @package Ekyna\Bundle\VerifiedReviewsBundle\DependencyInjection
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class EkynaVerifiedReviewsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');

        $definition = $container->getDefinition('ekyna_verified_reviews.updater.product');
        $definition->replaceArgument(3, $config['credential']['website_id']);

        $definition = $container->getDefinition('ekyna_verified_reviews.updater.review');
        $definition->replaceArgument(4, $config['credential']['website_id']);

        $definition = $container->getDefinition('ekyna_verified_reviews.notifier.order');
        $definition->setArgument(5, [
            'enable'       => $config['notification']['enable'],
            'delay'        => $config['notification']['delay'],
            'report_email' => $config['notification']['report_email'],
            'limit'        => $config['notification']['limit'],
            'website_id'   => $config['credential']['website_id'],
            'secret_key'   => $config['credential']['secret_key'],
            'debug'        => $container->getParameter('kernel.debug'),
        ]);

        $definition = $container->getDefinition('ekyna_verified_reviews.renderer.review');
        $definition->setArgument(5, $config['layout']);
    }
}
