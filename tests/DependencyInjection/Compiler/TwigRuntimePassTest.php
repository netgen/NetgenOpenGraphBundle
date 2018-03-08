<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Netgen\Bundle\OpenGraphBundle\DependencyInjection\Compiler\TwigRuntimePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class TwigRuntimePassTest extends AbstractCompilerPassTestCase
{
    public function testCompilerPassCollectsValidServices()
    {
        $twig = new Definition();
        $this->setDefinition('twig', $twig);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'twig',
            'addRuntimeLoader',
            array(
                new Reference('netgen_open_graph.templating.twig.runtime.loader'),
            )
        );
    }

    public function testCompilerPassWithTwigRuntimeLoaderService()
    {
        $twig = new Definition();
        $this->setDefinition('twig.runtime_loader', $twig);

        $this->compile();
    }

    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new TwigRuntimePass());
    }
}
