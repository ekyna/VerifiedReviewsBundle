<?php

declare(strict_types=1);

namespace Ekyna\Bundle\VerifiedReviewsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Ekyna\Bundle\VerifiedReviewsBundle\DependencyInjection
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ekyna_verified_reviews');

        $rootNode = $treeBuilder->getRootNode();

        $this->addCredentialSection($rootNode);
        $this->addNotificationSection($rootNode);
        $this->addLayoutSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Adds the `credential` section.
     */
    private function addCredentialSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('credential')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('secret_key')->defaultNull()->end()
                        ->scalarNode('website_id')->defaultNull()->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Adds the `notification` section.
     */
    private function addNotificationSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('notification')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enable')
                            ->defaultFalse()
                            ->info('Whether to enable order notification')
                        ->end()
                        ->scalarNode('report_email')
                            ->defaultNull()
                            ->info('Email to send the report to')
                        ->end()
                        ->integerNode('limit')
                            ->defaultValue(80)
                            ->info('Limit number of notifications sent')
                        ->end()
                        ->integerNode('delay')
                            ->defaultValue(7)
                            ->info('The number of days to wait before sending the reviews request email')
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Adds the `layout` section.
     */
    private function addLayoutSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('layout')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('columns')
                            ->defaultValue(2)
                            ->info('The number reviews columns')
                        ->end()
                        ->integerNode('rows')
                            ->defaultValue(8)
                            ->info('The number reviews rows per page')
                        ->end()
                        ->integerNode('width')
                            ->defaultValue(90)
                            ->info('The width of the rate\'s stars sprite')
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
