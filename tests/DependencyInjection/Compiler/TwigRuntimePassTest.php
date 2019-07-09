<?php

declare(strict_types=1);

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
            [
                new Reference('netgen_open_graph.templating.twig.runtime.loader'),
            ]
        );
    }

    public function testCompilerPassWithTwigRuntimeLoaderService()
    {
        $twig = new Definition();
        $this->setDefinition('twig.runtime_loader', $twig);

        $this->compile();

        // Fake assertion to disable risky warning
        self::assertTrue(true);
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new TwigRuntimePass());
    }
}
