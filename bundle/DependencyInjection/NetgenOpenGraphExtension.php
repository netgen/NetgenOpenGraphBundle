<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\DependencyInjection;

use Ibexa\Bundle\Core\DependencyInjection\Configuration\SiteAccessAware\ConfigurationProcessor;
use Ibexa\Bundle\Core\DependencyInjection\Configuration\SiteAccessAware\ContextualizerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class NetgenOpenGraphExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
        $loader->load('handlers.yaml');
        $loader->load('defaults.yaml');
        $loader->load('templating.yaml');

        // The following block is a workaround for Ibexa Platform configuration processor
        // not being able to merge arrays with numeric indexes.
        // It works by inserting a dummy 'all_content_types' subkey, basically converting
        // the array from numeric based indexes to string based indexes.
        if (!empty($config['system'])) {
            foreach ($config['system'] as $scope => &$handlers) {
                if (!empty($handlers['global_handlers'])) {
                    $handlers['global_handlers'] = [
                        'all_content_types' => $handlers['global_handlers'],
                    ];
                }
            }
        }

        $processor = new ConfigurationProcessor($container, 'netgen_open_graph');
        $processor->mapConfig(
            $config,
            static function ($scopeSettings, $currentScope, ContextualizerInterface $contextualizer) {
                foreach ($scopeSettings as $key => $value) {
                    $contextualizer->setContextualParameter($key, $currentScope, $value);
                }
            }
        );

        $processor->mapConfigArray('content_type_handlers', $config);
        $processor->mapConfigArray('global_handlers', $config);
    }
}
