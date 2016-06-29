<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\Templating\Twig\Extension;

use eZ\Publish\Core\Repository\Values\Content\Content;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Collector;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Renderer;
use Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension\NetgenOpenGraphExtension;
use Psr\Log\NullLogger;
use Twig_SimpleFunction;

class NetgenOpenGraphExtensionTest extends \PHPUnit_Framework_TestCase
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
        $this->collector = $this->getMockBuilder(Collector::class)
            ->disableOriginalConstructor()
            ->setMethods(array('collect'))
            ->getMock();

        $this->renderer = $this->getMockBuilder(Renderer::class)
            ->disableOriginalConstructor()
            ->setMethods(array('render'))
            ->getMock();

        $this->logger = $this->getMockBuilder(NullLogger::class)
            ->disableOriginalConstructor()
            ->setMethods(array('error'))
            ->getMock();

        $this->extension = new NetgenOpenGraphExtension($this->collector, $this->renderer, $this->logger);
    }

    public function testInstanceOfTwigExtensionInterface()
    {
        $this->assertInstanceOf(\Twig_ExtensionInterface::class, $this->extension);
    }

    public function testGetName()
    {
        $this->assertEquals('netgen_open_graph', $this->extension->getName());
    }

    public function testSetThrowExceptions()
    {
        $this->extension->setThrowExceptions(true);
    }

    public function testGetFunctions()
    {
        $functions = array(
            new Twig_SimpleFunction(
                'render_netgen_open_graph',
                array($this->extension, 'renderOpenGraphTags'),
                array('is_safe' => array('html'))
            ),
            new Twig_SimpleFunction(
                'get_netgen_open_graph',
                array($this->extension, 'getOpenGraphTags')
            ),
        );

        $result = $this->extension->getFunctions();

        $this->assertEquals($functions, $result);
    }

    public function testGetOpenGraphTags()
    {
        $items = array(
            new Item('tag1', 'some_value'),
        );

        $this->collector->expects($this->once())
            ->method('collect')
            ->willReturn($items);

        $result = $this->extension->getOpenGraphTags(new Content());

        $this->assertEquals($items, $result);
    }

    /**
     * @expectedException \Exception
     */
    public function testGetOpenGraphTagsWithThrowedException()
    {
        $this->collector->expects($this->once())
            ->method('collect')
            ->willThrowException(new \Exception());

        $this->extension->setThrowExceptions(true);
        $this->extension->getOpenGraphTags(new Content());
    }

    public function testGetOpenGraphTagsWithLoggedException()
    {
        $this->collector->expects($this->once())
            ->method('collect')
            ->willThrowException(new \Exception());

        $this->logger->expects($this->once())
            ->method('error');

        $this->extension->setThrowExceptions(false);
        $this->extension->getOpenGraphTags(new Content());
    }

    public function testRenderOpenGraphTags()
    {
        $items = array(
            new Item('tag1', 'some_value'),
        );

        $resultString = '<meta property="tag1" content="some_value" />';

        $this->collector->expects($this->once())
            ->method('collect')
            ->willReturn($items);

        $this->renderer->expects($this->once())
            ->method('render')
            ->willReturn($resultString);

        $result = $this->extension->renderOpenGraphTags(new Content());

        $this->assertEquals($resultString, $result);
    }

    /**
     * @expectedException \Exception
     */
    public function testRenderOpenGraphTagsWithThrowedException()
    {
        $this->collector->expects($this->once())
            ->method('collect')
            ->willReturn(array());

        $this->renderer->expects($this->once())
            ->method('render')
            ->willThrowException(new \Exception());

        $this->extension->setThrowExceptions(true);
        $this->extension->renderOpenGraphTags(new Content());
    }

    public function testRenderOpenGraphTagsWithLoggedException()
    {
        $this->collector->expects($this->once())
            ->method('collect')
            ->willReturn(array());

        $this->renderer->expects($this->once())
            ->method('render')
            ->willThrowException(new \Exception());

        $this->logger->expects($this->once())
            ->method('error');

        $this->extension->setThrowExceptions(false);
        $this->extension->renderOpenGraphTags(new Content());
    }
}
