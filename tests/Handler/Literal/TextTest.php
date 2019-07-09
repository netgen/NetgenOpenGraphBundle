<?php

declare(strict_types=1);

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

    protected function setUp(): void
    {
        $this->text = new Text();
    }

    public function testInstanceOfHandlerInterface(): void
    {
        self::assertInstanceOf(HandlerInterface::class, $this->text);
    }

    public function testGettingTagsWithEmptyParams(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument '\$params[0]' is invalid: Literal text handler requires the text to output.");

        $this->text->getMetaTags('some_tag', []);
    }

    public function testGettingTagsWithValidResult(): void
    {
        $result = $this->text->getMetaTags('some_tag', ['some_param']);

        self::assertIsArray($result);
        self::assertInstanceOf(Item::class, $result[0]);
        self::assertSame('some_tag', $result[0]->getTagName());
        self::assertSame('some_param', $result[0]->getTagValue());
    }
}
