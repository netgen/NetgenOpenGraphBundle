<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler\Literal;

use eZ\Publish\API\Repository\Exceptions\InvalidArgumentException;
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

    public function testGettingTagsWithEmptyParams()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument '\$params[0]' is invalid: Literal text handler requires the text to output.");

        $this->text->getMetaTags('some_tag', array());
    }

    public function testGettingTagsWithValidResult()
    {
        $result = $this->text->getMetaTags('some_tag', array('some_param'));

        $this->assertIsArray($result);
        $this->assertInstanceOf(Item::class, $result[0]);
        $this->assertEquals('some_tag', $result[0]->getTagName());
        $this->assertEquals('some_param', $result[0]->getTagValue());
    }
}
