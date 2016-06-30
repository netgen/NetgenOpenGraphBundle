<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler\FieldType;

use eZ\Publish\Core\Helper\FieldHelper;
use eZ\Publish\Core\Helper\TranslationHelper;
use eZ\Publish\Core\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\TextBlock\Value;
use Netgen\Bundle\OpenGraphBundle\Handler\FieldType\TextBlock;
use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;

class TextBlockTest extends HandlerBaseTest
{
    /**
     * @var TextBlock
     */
    protected $textBlock;

    public function setUp()
    {
        $this->fieldHelper = $this->getMockBuilder(FieldHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(array('isFieldEmpty'))
            ->getMock();

        $this->translationHelper = $this->getMockBuilder(TranslationHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getTranslatedField'))
            ->getMock();

        $this->content = $this->getMockBuilder(Content::class)
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $this->textBlock = new TextBlock($this->fieldHelper, $this->translationHelper);
        $this->textBlock->setContent($this->content);

        $this->field = new Field(array('value' => new Value()));
    }

    public function testInstanceOfHandlerInterface()
    {
        $this->assertInstanceOf(HandlerInterface::class, $this->textBlock);
    }

    /**
     * @expectedException \eZ\Publish\Core\Base\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Argument '$params[0]' is invalid: Field type handlers require at least a field identifier.
     */
    public function testGettingTagsWithoutFieldIdentifier()
    {
        $this->textBlock->getMetaTags('some_tag', array());
    }

    /**
     * @expectedException \eZ\Publish\Core\Base\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Argument '$params[0]' is invalid: Field 'some_value' does not exist in content.
     */
    public function testGettingTagsWithNonExistentField()
    {
        $this->translationHelper->expects($this->once())
            ->method('getTranslatedField')
            ->willReturn(null);

        $this->textBlock->getMetaTags('some_tag', array('some_value'));
    }

    /**
     * @expectedException \eZ\Publish\Core\Base\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Argument '$params[0]' is invalid: Netgen\Bundle\OpenGraphBundle\Handler\FieldType\TextBlock field type handler does not support field with identifier ''.
     */
    public function testGettingTagsWithUnsupportedField()
    {
        $this->translationHelper->expects($this->once())
            ->method('getTranslatedField')
            ->willReturn(new Field());

        $this->textBlock->getMetaTags('some_tag', array('some_value'));
    }

    public function testGettingTagsWithEmptyField()
    {
        $this->translationHelper->expects($this->once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $this->fieldHelper->expects($this->once())
            ->method('isFieldEmpty')
            ->willReturn(true);

        $this->textBlock->getMetaTags('some_tag', array('some_value'));
    }

    public function testGettingTags()
    {
        $this->translationHelper->expects($this->once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $this->textBlock->getMetaTags('some_tag', array('some_value'));
    }

    public function testGettingTagsWithMultipleArgumentsInArray()
    {
        $this->translationHelper->expects($this->once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $this->textBlock->getMetaTags('some_tag', array('some_value', 'some_value_2'));
    }
}
