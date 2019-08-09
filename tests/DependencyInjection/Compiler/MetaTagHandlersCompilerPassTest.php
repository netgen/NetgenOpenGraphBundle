<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Netgen\Bundle\OpenGraphBundle\DependencyInjection\Compiler\MetaTagHandlersCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Reference;

final class MetaTagHandlersCompilerPassTest extends AbstractCompilerPassTestCase
{
    public function testCompilerPassCollectsValidServices(): void
    {
        $handlerRegistry = new Definition();
        $this->setDefinition('netgen_open_graph.handler_registry', $handlerRegistry);

        $handlerOne = new Definition();
        $handlerOne->addTag('netgen_open_graph.meta_tag_handler', ['alias' => 'field_type/eztext']);
        $this->setDefinition('handler_one', $handlerOne);

        $handlerTwo = new Definition();
        $handlerTwo->addTag('netgen_open_graph.meta_tag_handler', ['alias' => 'literal/text']);
        $this->setDefinition('handler_two', $handlerTwo);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'netgen_open_graph.handler_registry',
            'addHandler',
            [
                'field_type/eztext',
                new Reference('handler_one'),
            ]
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'netgen_open_graph.handler_registry',
            'addHandler',
            [
                'literal/text',
                new Reference('handler_two'),
            ]
        );
    }

    public function testCompilerPassMustThrowExceptionIfActionServiceDoesNotHaveAlias(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('netgen_open_graph.meta_tag_handler service tag needs an "alias" attribute to identify the handler. None given.');

        $handlerRegistry = new Definition();
        $this->setDefinition('netgen_open_graph.handler_registry', $handlerRegistry);

        $handlerOne = new Definition();
        $handlerOne->addTag('netgen_open_graph.meta_tag_handler');
        $this->setDefinition('handler_one', $handlerOne);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'netgen_open_graph.handler_registry',
            'addHandler',
            [
                new Reference('handler_one'),
            ]
        );
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new MetaTagHandlersCompilerPass());
    }
}
