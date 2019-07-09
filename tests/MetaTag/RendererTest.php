<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\MetaTag;

use eZ\Publish\API\Repository\Exceptions\InvalidArgumentException;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Renderer;
use Netgen\Bundle\OpenGraphBundle\MetaTag\RendererInterface;
use PHPUnit\Framework\TestCase;

class RendererTest extends TestCase
{
    /**
     * @var Renderer
     */
    protected $renderer;

    protected function setUp(): void
    {
        $this->renderer = new Renderer();
    }

    public function testInstanceOfRendererInterface()
    {
        self::assertInstanceOf(RendererInterface::class, $this->renderer);
    }

    public function testRenderWithEmptyArray()
    {
        $result = $this->renderer->render([]);

        self::assertSame('', $result);
    }

    public function testRenderWithInvalidItem()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument 'metaTags' is invalid: Cannot render meta tag, not an instance of \\Netgen\\Bundle\\OpenGraphBundle\\MetaTag\\Item");

        $this->renderer->render(['test']);
    }

    public function testRender()
    {
        $item = new Item('name', 'value');
        $result = $this->renderer->render([$item]);

        self::assertSame("<meta property=\"name\" content=\"value\" />\n", $result);
    }

    public function testItProperlyEscapesValue()
    {
        $item = new Item('name', 'val<javascript></javascript>ue');
        $result = $this->renderer->render([$item]);

        self::assertSame("<meta property=\"name\" content=\"val&lt;javascript&gt;&lt;/javascript&gt;ue\" />\n", $result);
    }
}
