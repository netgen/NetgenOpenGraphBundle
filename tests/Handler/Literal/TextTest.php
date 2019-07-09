<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler\Literal;

use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use Netgen\Bundle\OpenGraphBundle\Handler\Literal\Text;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    /**
     * @var Text
     */
    protected $text;

    public function setUp(): void
    {
        $this->text = new Text();
    }

    public function testInstanceOfHandlerInterface()
    {
        $this->assertInstanceOf(HandlerInterface::class, $this->text);
    }

    /**
     * @expectedException \eZ\Publish\Core\Base\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Argument '$params[0]' is invalid: Literal text handler requires the text to output.
     */
    public function testGettingTagsWithEmptyParams()
    {
        $this->text->getMetaTags('some_tag', array());
    }

    public function testGettingTagsWithValidResult()
    {
        $result = $this->text->getMetaTags('some_tag', array('some_param'));

        $this->assertInternalType('array', $result);
        $this->assertInstanceOf(Item::class, $result[0]);
        $this->assertEquals('some_tag', $result[0]->getTagName());
        $this->assertEquals('some_param', $result[0]->getTagValue());
    }
}
