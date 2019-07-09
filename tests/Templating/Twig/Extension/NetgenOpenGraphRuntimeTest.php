<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\Templating\Twig\Extension;

use eZ\Publish\Core\Repository\Values\Content\Content;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Collector;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Renderer;
use Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension\NetgenOpenGraphRuntime;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class NetgenOpenGraphRuntimeTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension\NetgenOpenGraphRuntime
     */
    protected $runtime;

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

    public function setUp(): void
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

        $this->runtime = new NetgenOpenGraphRuntime($this->collector, $this->renderer, $this->logger);
    }

    public function testInstanceOfTwigExtensionInterface()
    {
        $this->assertInstanceOf(NetgenOpenGraphRuntime::class, $this->runtime);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testSetThrowExceptions()
    {
        $this->runtime->setThrowExceptions(true);
    }

    public function testGetOpenGraphTags()
    {
        $items = array(
            new Item('tag1', 'some_value'),
        );

        $this->collector->expects($this->once())
            ->method('collect')
            ->willReturn($items);

        $result = $this->runtime->getOpenGraphTags(new Content());

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

        $this->runtime->setThrowExceptions(true);
        $this->runtime->getOpenGraphTags(new Content());
    }

    public function testGetOpenGraphTagsWithLoggedException()
    {
        $this->collector->expects($this->once())
            ->method('collect')
            ->willThrowException(new \Exception());

        $this->logger->expects($this->once())
            ->method('error');

        $this->runtime->setThrowExceptions(false);
        $this->runtime->getOpenGraphTags(new Content());
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

        $result = $this->runtime->renderOpenGraphTags(new Content());

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

        $this->runtime->setThrowExceptions(true);
        $this->runtime->renderOpenGraphTags(new Content());
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

        $this->runtime->setThrowExceptions(false);
        $this->runtime->renderOpenGraphTags(new Content());
    }
}
