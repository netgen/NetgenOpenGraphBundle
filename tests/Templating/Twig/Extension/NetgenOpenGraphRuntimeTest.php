<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Templating\Twig\Extension;

use Exception;
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

    protected function setUp(): void
    {
        $this->collector = $this->getMockBuilder(Collector::class)
            ->disableOriginalConstructor()
            ->setMethods(['collect'])
            ->getMock();

        $this->renderer = $this->getMockBuilder(Renderer::class)
            ->disableOriginalConstructor()
            ->setMethods(['render'])
            ->getMock();

        $this->logger = $this->getMockBuilder(NullLogger::class)
            ->disableOriginalConstructor()
            ->setMethods(['error'])
            ->getMock();

        $this->runtime = new NetgenOpenGraphRuntime($this->collector, $this->renderer, $this->logger);
    }

    public function testInstanceOfTwigExtensionInterface()
    {
        self::assertInstanceOf(NetgenOpenGraphRuntime::class, $this->runtime);
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
        $items = [
            new Item('tag1', 'some_value'),
        ];

        $this->collector->expects(self::once())
            ->method('collect')
            ->willReturn($items);

        $result = $this->runtime->getOpenGraphTags(new Content());

        self::assertSame($items, $result);
    }

    public function testGetOpenGraphTagsWithThrowedException()
    {
        $this->expectException(Exception::class);

        $this->collector->expects(self::once())
            ->method('collect')
            ->willThrowException(new \Exception());

        $this->runtime->setThrowExceptions(true);
        $this->runtime->getOpenGraphTags(new Content());
    }

    public function testGetOpenGraphTagsWithLoggedException()
    {
        $this->collector->expects(self::once())
            ->method('collect')
            ->willThrowException(new \Exception());

        $this->logger->expects(self::once())
            ->method('error');

        $this->runtime->setThrowExceptions(false);
        $this->runtime->getOpenGraphTags(new Content());
    }

    public function testRenderOpenGraphTags()
    {
        $items = [
            new Item('tag1', 'some_value'),
        ];

        $resultString = '<meta property="tag1" content="some_value" />';

        $this->collector->expects(self::once())
            ->method('collect')
            ->willReturn($items);

        $this->renderer->expects(self::once())
            ->method('render')
            ->willReturn($resultString);

        $result = $this->runtime->renderOpenGraphTags(new Content());

        self::assertSame($resultString, $result);
    }

    public function testRenderOpenGraphTagsWithThrowedException()
    {
        $this->expectException(Exception::class);

        $this->collector->expects(self::once())
            ->method('collect')
            ->willReturn([]);

        $this->renderer->expects(self::once())
            ->method('render')
            ->willThrowException(new \Exception());

        $this->runtime->setThrowExceptions(true);
        $this->runtime->renderOpenGraphTags(new Content());
    }

    public function testRenderOpenGraphTagsWithLoggedException()
    {
        $this->collector->expects(self::once())
            ->method('collect')
            ->willReturn([]);

        $this->renderer->expects(self::once())
            ->method('render')
            ->willThrowException(new \Exception());

        $this->logger->expects(self::once())
            ->method('error');

        $this->runtime->setThrowExceptions(false);
        $this->runtime->renderOpenGraphTags(new Content());
    }
}
