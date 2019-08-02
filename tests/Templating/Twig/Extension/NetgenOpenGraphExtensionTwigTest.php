<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Templating\Twig\Extension;

use Netgen\Bundle\OpenGraphBundle\MetaTag\Collector;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Renderer;
use Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension\NetgenOpenGraphExtension;
use Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension\NetgenOpenGraphRuntime;
use Psr\Log\NullLogger;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Twig\Test\IntegrationTestCase;

class NetgenOpenGraphExtensionTwigTest extends IntegrationTestCase
{
    /**
     * @var \Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension\NetgenOpenGraphExtension
     */
    protected $extension;

    /**
     * @var \Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension\NetgenOpenGraphRuntime
     */
    protected $runtime;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $collector;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $renderer;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $logger;

    protected function setUp(): void
    {
        /** @var Item[] $items */
        $items = [
            new Item('tag1', 'some_value'),
        ];

        $this->collector = $this->getMockBuilder(Collector::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['collect'])
            ->getMock();

        $this->collector->method('collect')
            ->willReturn($items);

        $this->renderer = $this->getMockBuilder(Renderer::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['render'])
            ->getMock();

        $html = '';
        foreach ($items as $item) {
            $html .= "<meta property=\"{$item->getTagName()}\" content=\"{$item->getTagValue()}\" />\n";
        }

        $this->renderer->method('render')
            ->willReturn($html);

        $this->logger = $this->getMockBuilder(NullLogger::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['error'])
            ->getMock();

        $this->extension = new NetgenOpenGraphExtension();
        $this->runtime = new NetgenOpenGraphRuntime($this->collector, $this->renderer, $this->logger);
    }

    protected function getFixturesDir(): string
    {
        return __DIR__ . '/_fixtures/';
    }

    protected function getExtensions(): array
    {
        return [$this->extension];
    }

    protected function getRuntimeLoaders(): array
    {
        return [
            new FactoryRuntimeLoader(
                [
                    NetgenOpenGraphRuntime::class => function () {
                        return $this->runtime;
                    },
                ]
            ),
        ];
    }
}
