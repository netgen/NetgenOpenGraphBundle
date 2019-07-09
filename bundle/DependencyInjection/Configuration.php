<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\DependencyInjection;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\Configuration as SiteAccessConfiguration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration extends SiteAccessConfiguration
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('netgen_open_graph');
        $rootNode = $treeBuilder->getRootNode()
            ->fixXmlConfig('content_type_handler')
            ->fixXmlConfig('global_handler');

        $this->generateScopeBaseNode($rootNode)
            ->arrayNode('content_type_handlers')
                ->useAttributeAsKey('content_type')
                ->normalizeKeys(false)
                ->prototype('array')
                    ->requiresAtLeastOneElement()
                    ->normalizeKeys(false)
                    ->prototype('array')
                        ->children()
                            ->scalarNode('handler')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('tag')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->arrayNode('params')
                                ->prototype('variable')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('global_handlers')
                ->normalizeKeys(false)
                ->prototype('array')
                    ->children()
                        ->scalarNode('handler')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('tag')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->arrayNode('params')
                            ->prototype('variable')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
