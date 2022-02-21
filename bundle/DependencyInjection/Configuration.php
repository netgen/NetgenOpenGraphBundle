<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\DependencyInjection;

use Ibexa\Bundle\Core\DependencyInjection\Configuration\SiteAccessAware\Configuration as SiteAccessConfiguration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

final class Configuration extends SiteAccessConfiguration
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
                ->arrayPrototype()
                    ->requiresAtLeastOneElement()
                    ->normalizeKeys(false)
                    ->arrayPrototype()
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
                                ->variablePrototype()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('global_handlers')
                ->normalizeKeys(false)
                ->arrayPrototype()
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
                            ->variablePrototype()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
