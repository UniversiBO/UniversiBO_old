<?php

namespace Universibo\Bundle\SSOBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('universibo_sso');

        $rootNode
            ->children()
                ->arrayNode('idp_url')
                    ->children()
                        ->scalarNode('base')->end()
                        ->scalarNode('info')->end()
                        ->scalarNode('logout')->end()
                    ->end()
                ->end()
                ->arrayNode('route')
                    ->children()
                        ->scalarNode('after_logout')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
