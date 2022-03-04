<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\MetaTag;

use Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Renderer;
use Netgen\Bundle\OpenGraphBundle\MetaTag\RendererInterface;
use PHPUnit\Framework\TestCase;

final class RendererTest extends TestCase
{
    private Renderer $renderer;

    protected function setUp(): void
    {
        $this->renderer = new Renderer();
    }

    public function testInstanceOfRendererInterface(): void
    {
        self::assertInstanceOf(RendererInterface::class, $this->renderer);
    }

    public function testRenderWithEmptyArray(): void
    {
        $result = $this->renderer->render();

        self::assertSame('', $result);
    }

    public function testRenderWithInvalidItem(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument 'metaTags' is invalid: Cannot render meta tag, not an instance of \\Netgen\\Bundle\\OpenGraphBundle\\MetaTag\\Item");

        $this->renderer->render(['test']);
    }

    public function testRender(): void
    {
        $item = new Item('name', 'value');
        $result = $this->renderer->render([$item]);

        self::assertSame("<meta property=\"name\" content=\"value\" />\n", $result);
    }

    public function testItProperlyEscapesValue(): void
    {
        $item = new Item('name', 'val<javascript></javascript>ue');
        $result = $this->renderer->render([$item]);

        self::assertSame("<meta property=\"name\" content=\"val&lt;javascript&gt;&lt;/javascript&gt;ue\" />\n", $result);
    }
}
