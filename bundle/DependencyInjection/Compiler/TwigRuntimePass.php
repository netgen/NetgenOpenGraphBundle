<?php

namespace Netgen\Bundle\OpenGraphBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TwigRuntimePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->has('twig.runtime_loader')) {
            // If official Twig runtime loader exists,
            // we skip using our custom runtime loader
            return;
        }

        if (!$container->hasDefinition('twig')) {
            return;
        }

        $twig = $container->findDefinition('twig');

        $twig->addMethodCall(
            'addRuntimeLoader',
            array(
                new Reference('netgen_open_graph.templating.twig.runtime.loader'),
            )
        );
    }
}
