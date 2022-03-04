<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler\FieldType;

use Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException;
use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Ibexa\Core\Helper\FieldHelper;
use Ibexa\Core\Repository\Values\Content\Content;
use Ibexa\FieldTypeRichText\FieldType\RichText\Value;
use Netgen\Bundle\OpenGraphBundle\Handler\FieldType\RichText;
use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class RichTextTest extends TestCase
{
    private MockObject $fieldHelper;

    private MockObject $content;

    private Field $field;

    private RichText $richText;

    protected function setUp(): void
    {
        $this->fieldHelper = $this->getMockBuilder(FieldHelper::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isFieldEmpty'])
            ->getMock();

        $this->content = $this->getMockBuilder(Content::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->richText = new RichText($this->fieldHelper);
        $this->richText->setContent($this->content);

        $this->field = new Field(['value' => new Value(), 'fieldDefIdentifier' => 'field']);
    }

    public function testInstanceOfHandlerInterface(): void
    {
        self::assertInstanceOf(HandlerInterface::class, $this->richText);
    }

    public function testGettingTagsWithoutFieldIdentifier(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument '\$params[0]' is invalid: Field type handlers require at least a field identifier.");

        $this->richText->getMetaTags('some_tag');
    }

    public function testGettingTagsWithNonExistentField(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument '\$params[0]' is invalid: Field 'some_value' does not exist in content.");

        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn(null);

        $this->richText->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithUnsupportedField(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument '\$params[0]' is invalid: Netgen\\Bundle\\OpenGraphBundle\\Handler\\FieldType\\RichText field type handler does not support field with identifier 'field'.");

        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn(new Field(['fieldDefIdentifier' => 'field']));

        $this->richText->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithEmptyField(): void
    {
        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn($this->field);

        $this->fieldHelper->expects(self::once())
            ->method('isFieldEmpty')
            ->willReturn(true);

        $this->richText->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTags(): void
    {
        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn($this->field);

        $this->richText->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithMultipleArgumentsInArray(): void
    {
        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn($this->field);

        $this->richText->getMetaTags('some_tag', ['some_value', 'some_value_2']);
    }
}
