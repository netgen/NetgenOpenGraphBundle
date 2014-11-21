<?php

namespace Netgen\Bundle\OpenGraphBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\Configuration as SiteAccessConfiguration;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration extends SiteAccessConfiguration
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root( 'netgen_open_graph' )
            ->fixXmlConfig( 'content_type_handler' )
            ->fixXmlConfig( 'global_handler' );

        $this->generateScopeBaseNode( $rootNode )
            ->arrayNode( 'content_type_handlers' )
                ->useAttributeAsKey( 'content_type' )
                ->normalizeKeys( false )
                ->prototype( 'array' )
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey( 'handler' )
                    ->normalizeKeys( false )
                    ->prototype( 'array' )
                        ->children()
                            ->scalarNode( 'tag' )
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->arrayNode( 'params' )
                                ->prototype( 'variable' )->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode( 'global_handlers' )
                ->useAttributeAsKey( 'handler' )
                ->normalizeKeys( false )
                ->prototype( 'array' )
                    ->children()
                        ->scalarNode( 'tag' )
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->arrayNode( 'params' )
                            ->prototype( 'variable' )->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
