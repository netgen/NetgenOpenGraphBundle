<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Reference;

class MetaTagHandlersCompilerPass implements CompilerPassInterface
{
    /**
     * Adds all registered meta tag handlers to the registry.
     */
    public function process(ContainerBuilder $container): void
    {
        $metaTagHandlers = $container->findTaggedServiceIds('netgen_open_graph.meta_tag_handler');
        if (!empty($metaTagHandlers) && $container->hasDefinition('netgen_open_graph.handler_registry')) {
            $handlerRegistry = $container->getDefinition('netgen_open_graph.handler_registry');
            foreach ($metaTagHandlers as $serviceId => $metaTagHandler) {
                if (!isset($metaTagHandler[0]['alias'])) {
                    throw new LogicException(
                        'netgen_open_graph.meta_tag_handler service tag needs an "alias" attribute to identify the handler. None given.'
                    );
                }
                $handlerRegistry->addMethodCall(
                    'addHandler',
                    [
                        $metaTagHandler[0]['alias'],
                        new Reference($serviceId),
                    ]
                );
            }
        }
    }
}
