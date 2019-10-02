<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler\FieldType;

use eZ\Publish\API\Repository\Exceptions\InvalidArgumentException;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\TextLine\Value;
use eZ\Publish\Core\Helper\FieldHelper;
use eZ\Publish\Core\Repository\Values\Content\Content;
use Netgen\Bundle\OpenGraphBundle\Handler\FieldType\TextLine;
use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use PHPUnit\Framework\TestCase;

final class TextLineTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $fieldHelper;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $content;

    /**
     * @var \eZ\Publish\API\Repository\Values\Content\Field
     */
    private $field;

    /**
     * @var \Netgen\Bundle\OpenGraphBundle\Handler\FieldType\TextLine
     */
    private $textLine;

    protected function setUp(): void
    {
        $this->fieldHelper = $this->getMockBuilder(FieldHelper::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isFieldEmpty'])
            ->getMock();

        $this->content = $this->getMockBuilder(Content::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->textLine = new TextLine($this->fieldHelper);
        $this->textLine->setContent($this->content);

        $this->field = new Field(['value' => new Value(), 'fieldDefIdentifier' => 'field']);
    }

    public function testInstanceOfHandlerInterface(): void
    {
        self::assertInstanceOf(HandlerInterface::class, $this->textLine);
    }

    public function testGettingTagsWithoutFieldIdentifier(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument '\$params[0]' is invalid: Field type handlers require at least a field identifier.");

        $this->textLine->getMetaTags('some_tag');
    }

    public function testGettingTagsWithNonExistentField(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument '\$params[0]' is invalid: Field 'some_value' does not exist in content.");

        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn(null);

        $this->textLine->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithUnsupportedField(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument '\$params[0]' is invalid: Netgen\\Bundle\\OpenGraphBundle\\Handler\\FieldType\\TextLine field type handler does not support field with identifier 'field'.");

        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn(new Field(['fieldDefIdentifier' => 'field']));

        $this->textLine->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithEmptyField(): void
    {
        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn($this->field);

        $this->fieldHelper->expects(self::once())
            ->method('isFieldEmpty')
            ->willReturn(true);

        $this->textLine->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTags(): void
    {
        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn($this->field);

        $this->textLine->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithMultipleArgumentsInArray(): void
    {
        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn($this->field);

        $this->textLine->getMetaTags('some_tag', ['some_value', 'some_value_2']);
    }
}
