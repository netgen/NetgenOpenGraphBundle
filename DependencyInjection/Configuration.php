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
        $rootNode = $treeBuilder->root( 'netgen_open_graph' );

        $this->generateScopeBaseNode( $rootNode )
            ->arrayNode( 'content_type_handlers' )
                ->prototype( 'array' )
                    ->requiresAtLeastOneElement()
                    ->prototype( 'array' )
                        ->children()
                            ->scalarNode( 'handler' )
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
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
            ->scalarNode( 'facebook_app_id' )->end();

        return $treeBuilder;
    }
}
