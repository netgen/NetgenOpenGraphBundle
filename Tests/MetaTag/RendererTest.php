<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\MetaTag;

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

    public function setUp()
    {
        $this->renderer = new Renderer();
    }

    public function testInstanceOfRendererInterface()
    {
        $this->assertInstanceOf(RendererInterface::class, $this->renderer);
    }

    public function testRenderWithEmptyArray()
    {
        $result = $this->renderer->render(array());

        $this->assertEquals('', $result);
    }

    /**
     * @expectedException  \eZ\Publish\Core\Base\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Argument 'metaTags' is invalid: Cannot render meta tag, not an instance of \Netgen\Bundle\OpenGraphBundle\MetaTag\Item
     */
    public function testRenderWithInvalidItem()
    {
        $this->renderer->render(array('test'));
    }

    public function testRender()
    {
        $item = new Item('name', 'value');
        $result = $this->renderer->render(array($item));

        $this->assertEquals("<meta property=\"name\" content=\"value\" />\n", $result);
    }

    public function testItProperlyEscapesValue()
    {
        $item = new Item('name', 'val<javascript></javascript>ue');
        $result = $this->renderer->render(array($item));

        $this->assertEquals("<meta property=\"name\" content=\"val&lt;javascript&gt;&lt;/javascript&gt;ue\" />\n", $result);
    }
}
