<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler\FieldType;

use eZ\Publish\API\Repository\Exceptions\InvalidArgumentException;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\TextLine\Value;
use eZ\Publish\Core\Helper\FieldHelper;
use eZ\Publish\Core\Helper\TranslationHelper;
use eZ\Publish\Core\Repository\Values\Content\Content;
use Netgen\Bundle\OpenGraphBundle\Handler\FieldType\TextLine;
use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;

class TextLineTest extends HandlerBaseTest
{
    /**
     * @var TextLine
     */
    protected $textLine;

    protected function setUp(): void
    {
        $this->fieldHelper = $this->getMockBuilder(FieldHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(['isFieldEmpty'])
            ->getMock();

        $this->translationHelper = $this->getMockBuilder(TranslationHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(['getTranslatedField'])
            ->getMock();

        $this->content = $this->getMockBuilder(Content::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $this->textLine = new TextLine($this->fieldHelper, $this->translationHelper);
        $this->textLine->setContent($this->content);

        $this->field = new Field(['value' => new Value()]);
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

        $this->translationHelper->expects(self::once())
            ->method('getTranslatedField')
            ->willReturn(null);

        $this->textLine->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithUnsupportedField(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument '\$params[0]' is invalid: Netgen\\Bundle\\OpenGraphBundle\\Handler\\FieldType\\TextLine field type handler does not support field with identifier ''.");

        $this->translationHelper->expects(self::once())
            ->method('getTranslatedField')
            ->willReturn(new Field());

        $this->textLine->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithEmptyField(): void
    {
        $this->translationHelper->expects(self::once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $this->fieldHelper->expects(self::once())
            ->method('isFieldEmpty')
            ->willReturn(true);

        $this->textLine->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTags(): void
    {
        $this->translationHelper->expects(self::once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $this->textLine->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithMultipleArgumentsInArray(): void
    {
        $this->translationHelper->expects(self::once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $this->textLine->getMetaTags('some_tag', ['some_value', 'some_value_2']);
    }
}
