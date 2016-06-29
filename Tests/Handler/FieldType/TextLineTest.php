<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler\FieldType;

use eZ\Publish\Core\Helper\FieldHelper;
use eZ\Publish\Core\Helper\TranslationHelper;
use Netgen\Bundle\OpenGraphBundle\Handler\FieldType\TextLine;
use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;

class TextLineTest extends HandlerBaseTest
{
    /**
     * @var TextLine
     */
    protected $textLine;
    
    public function setUp() 
    {
        $this->fieldHelper = $this->getMockBuilder(FieldHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $this->translationHelper = $this->getMockBuilder(TranslationHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $this->textLine = new TextLine($this->fieldHelper, $this->translationHelper);
    }

    public function testInstanceOfHandlerInterface()
    {
        $this->assertInstanceOf(HandlerInterface::class, $this->textLine);
    }

    /**
     * @expectedException \eZ\Publish\Core\Base\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Argument '$params[0]' is invalid: Field type handlers require at least a field identifier.
     */
    public function testGettingTagsWithoutFieldIdentifier()
    {
        $this->textLine->getMetaTags('some_tag', array());
    }
}
