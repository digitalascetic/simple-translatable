<?php

namespace DigitalAscetic\SimpleTranslatable\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('simple_translatable');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('add_locale_route_param')->defaultFalse()->end()
                ->scalarNode('default_locale')->isRequired()->end()
                ->arrayNode('locales')
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

}
