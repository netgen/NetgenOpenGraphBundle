<?php

namespace Netgen\Bundle\OpenGraphBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ConfigurationProcessor;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ContextualizerInterface;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NetgenOpenGraphExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('handlers.yml');
        $loader->load('defaults.yml');
        $loader->load('templating.yml');

        // The following block is a workaround for eZ Publish configuration processor
        // not being able to merge arrays with numeric indexes.
        // It works by inserting a dummy 'all_content_types' subkey, basically converting
        // the array from numeric based indexes to string based indexes.
        if (!empty($config['system'])) {
            foreach ($config['system'] as $scope => &$handlers) {
                if (!empty($handlers['global_handlers'])) {
                    $handlers['global_handlers'] = array(
                        'all_content_types' => $handlers['global_handlers'],
                    );
                }
            }
        }

        $processor = new ConfigurationProcessor($container, 'netgen_open_graph');
        $processor->mapConfig(
            $config,
            function ($scopeSettings, $currentScope, ContextualizerInterface $contextualizer) {
                foreach ($scopeSettings as $key => $value) {
                    $contextualizer->setContextualParameter($key, $currentScope, $value);
                }
            }
        );

        $processor->mapConfigArray('content_type_handlers', $config);
        $processor->mapConfigArray('global_handlers', $config);
    }
}
