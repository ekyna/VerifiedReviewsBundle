<?php

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
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ekyna_verified_reviews');

        $this->addCredentialSection($rootNode);
        $this->addNotificationSection($rootNode);
        $this->addLayoutSection($rootNode);
        $this->addPoolsSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Adds the `credential` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addCredentialSection(ArrayNodeDefinition $node)
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
     *
     * @param ArrayNodeDefinition $node
     */
    private function addNotificationSection(ArrayNodeDefinition $node)
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
     *
     * @param ArrayNodeDefinition $node
     */
    private function addLayoutSection(ArrayNodeDefinition $node)
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

    /**
     * Adds the `pools` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addPoolsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('pools')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('review')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('entity')
                                    ->defaultValue('Ekyna\Bundle\VerifiedReviewsBundle\Entity\Review')
                                ->end()
                                ->scalarNode('repository')
                                    ->defaultValue('Ekyna\Bundle\VerifiedReviewsBundle\Repository\ReviewRepository')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
