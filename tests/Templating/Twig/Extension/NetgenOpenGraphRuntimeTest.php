<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Templating\Twig\Extension;

use Exception;
use Ibexa\Core\Repository\Values\Content\Content;
use Netgen\Bundle\OpenGraphBundle\MetaTag\CollectorInterface;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use Netgen\Bundle\OpenGraphBundle\MetaTag\RendererInterface;
use Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension\NetgenOpenGraphRuntime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

final class NetgenOpenGraphRuntimeTest extends TestCase
{
    private NetgenOpenGraphRuntime $runtime;

    private MockObject $collector;

    private MockObject $renderer;

    private MockObject $logger;

    protected function setUp(): void
    {
        $this->collector = $this->getMockBuilder(CollectorInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['collect'])
            ->getMock();

        $this->renderer = $this->getMockBuilder(RendererInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['render'])
            ->getMock();

        $this->logger = $this->getMockBuilder(NullLogger::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['error'])
            ->getMock();

        $this->runtime = new NetgenOpenGraphRuntime($this->collector, $this->renderer, $this->logger);
    }

    public function testInstanceOfTwigExtensionInterface(): void
    {
        self::assertInstanceOf(NetgenOpenGraphRuntime::class, $this->runtime);
    }

    public function testGetOpenGraphTags(): void
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

    public function testGetOpenGraphTagsWithThrownException(): void
    {
        $this->expectException(Exception::class);

        $this->collector->expects(self::once())
            ->method('collect')
            ->willThrowException(new Exception());

        $this->runtime->getOpenGraphTags(new Content());
    }

    public function testGetOpenGraphTagsWithLoggedException(): void
    {
        $this->collector->expects(self::once())
            ->method('collect')
            ->willThrowException(new Exception());

        $this->logger->expects(self::once())
            ->method('error');

        $this->runtime->setThrowExceptions(false);
        $this->runtime->getOpenGraphTags(new Content());
    }

    public function testRenderOpenGraphTags(): void
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

    public function testRenderOpenGraphTagsWithThrownException(): void
    {
        $this->expectException(Exception::class);

        $this->collector->expects(self::once())
            ->method('collect')
            ->willReturn([]);

        $this->renderer->expects(self::once())
            ->method('render')
            ->willThrowException(new Exception());

        $this->runtime->renderOpenGraphTags(new Content());
    }

    public function testRenderOpenGraphTagsWithLoggedException(): void
    {
        $this->collector->expects(self::once())
            ->method('collect')
            ->willReturn([]);

        $this->renderer->expects(self::once())
            ->method('render')
            ->willThrowException(new Exception());

        $this->logger->expects(self::once())
            ->method('error');

        $this->runtime->setThrowExceptions(false);
        $this->runtime->renderOpenGraphTags(new Content());
    }
}
