<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler\FieldType;

use Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException;
use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Ibexa\Core\FieldType\TextBlock\Value;
use Ibexa\Core\Helper\FieldHelper;
use Ibexa\Core\Repository\Values\Content\Content;
use Netgen\Bundle\OpenGraphBundle\Handler\FieldType\TextBlock;
use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class TextBlockTest extends TestCase
{
    private MockObject $fieldHelper;

    private MockObject $content;

    private Field $field;

    private TextBlock $textBlock;

    protected function setUp(): void
    {
        $this->fieldHelper = $this->getMockBuilder(FieldHelper::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isFieldEmpty'])
            ->getMock();

        $this->content = $this->getMockBuilder(Content::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->textBlock = new TextBlock($this->fieldHelper);
        $this->textBlock->setContent($this->content);

        $this->field = new Field(['value' => new Value(), 'fieldDefIdentifier' => 'field']);
    }

    public function testInstanceOfHandlerInterface(): void
    {
        self::assertInstanceOf(HandlerInterface::class, $this->textBlock);
    }

    public function testGettingTagsWithoutFieldIdentifier(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument '\$params[0]' is invalid: Field type handlers require at least a field identifier.");

        $this->textBlock->getMetaTags('some_tag');
    }

    public function testGettingTagsWithNonExistentField(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument '\$params[0]' is invalid: Field 'some_value' does not exist in content.");

        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn(null);

        $this->textBlock->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithUnsupportedField(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument '\$params[0]' is invalid: Netgen\\Bundle\\OpenGraphBundle\\Handler\\FieldType\\TextBlock field type handler does not support field with identifier 'field'.");

        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn(new Field(['fieldDefIdentifier' => 'field']));

        $this->textBlock->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithEmptyField(): void
    {
        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn($this->field);

        $this->fieldHelper->expects(self::once())
            ->method('isFieldEmpty')
            ->willReturn(true);

        $this->textBlock->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTags(): void
    {
        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn($this->field);

        $this->textBlock->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithMultipleArgumentsInArray(): void
    {
        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn($this->field);

        $this->textBlock->getMetaTags('some_tag', ['some_value', 'some_value_2']);
    }
}
