<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\Templating\Twig\Extension;

use Netgen\Bundle\OpenGraphBundle\MetaTag\Collector;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Renderer;
use Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension\NetgenOpenGraphExtension;
use Psr\Log\NullLogger;

class NetgenOpenGraphExtensionTwigTest extends \Twig_Test_IntegrationTestCase
{
    /**
     * @var NetgenOpenGraphExtension
     */
    protected $extension;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $collector;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $renderer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $logger;

    public function setUp()
    {
        /** @var Item[] $items */
        $items = array(
            new Item('tag1', 'some_value'),
        );

        $this->collector = $this->getMockBuilder(Collector::class)
            ->disableOriginalConstructor()
            ->setMethods(array('collect'))
            ->getMock();

        $this->collector->method('collect')
            ->willReturn($items);

        $this->renderer = $this->getMockBuilder(Renderer::class)
            ->disableOriginalConstructor()
            ->setMethods(array('render'))
            ->getMock();

        $html = '';
        foreach ($items as $item) {
            $html .= "<meta property=\"{$item->getTagName()}\" content=\"{$item->getTagValue()}\" />\n";
        }

        $this->renderer->method('render')
            ->willReturn($html);

        $this->logger = $this->createMock(NullLogger::class);

        $this->extension = new NetgenOpenGraphExtension($this->collector, $this->renderer, $this->logger);
    }

    /**
     * @return string
     */
    protected function getFixturesDir()
    {
        return __DIR__ . '/_fixtures/';
    }

    /**
     * @return \Twig_ExtensionInterface[]
     */
    protected function getExtensions()
    {
        return array($this->extension);
    }
}
