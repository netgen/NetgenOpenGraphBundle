<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Netgen\Bundle\OpenGraphBundle\DependencyInjection\Compiler\MetaTagHandlersCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class MetaTagHandlersCompilerPassTest extends AbstractCompilerPassTestCase
{
    public function testCompilerPassCollectsValidServices()
    {
        $handlerRegistry = new Definition();
        $this->setDefinition('netgen_open_graph.handler_registry', $handlerRegistry);

        $handlerOne = new Definition();
        $handlerOne->addTag('netgen_open_graph.meta_tag_handler', array('alias' => 'field_type/eztext'));
        $this->setDefinition('handler_one', $handlerOne);

        $handlerTwo = new Definition();
        $handlerTwo->addTag('netgen_open_graph.meta_tag_handler', array('alias' => 'literal/text'));
        $this->setDefinition('handler_two', $handlerTwo);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'netgen_open_graph.handler_registry',
            'addHandler',
            array(
                'field_type/eztext',
                new Reference('handler_one'),
            )
        );

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'netgen_open_graph.handler_registry',
            'addHandler',
            array(
                'literal/text',
                new Reference('handler_two'),
            )
        );
    }

    /**
     * @expectedException \Symfony\Component\DependencyInjection\Exception\LogicException
     * @expectedExceptionMessage netgen_open_graph.meta_tag_handler service tag needs an "alias" attribute to identify the handler. None given.
     */
    public function testCompilerPassMustThrowExceptionIfActionServiceHasntGotAlias()
    {
        $handlerRegistry = new Definition();
        $this->setDefinition('netgen_open_graph.handler_registry', $handlerRegistry);

        $handlerOne = new Definition();
        $handlerOne->addTag('netgen_open_graph.meta_tag_handler');
        $this->setDefinition('handler_one', $handlerOne);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'netgen_open_graph.handler_registry',
            'addHandler',
            array(
                new Reference('handler_one'),
            )
        );
    }

    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new MetaTagHandlersCompilerPass());
    }
}
