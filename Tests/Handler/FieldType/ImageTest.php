<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler\FieldType;

use eZ\Bundle\EzPublishCoreBundle\Imagine\AliasGenerator;
use eZ\Publish\API\Repository\Exceptions\InvalidVariationException;
use eZ\Publish\Core\Helper\FieldHelper;
use eZ\Publish\Core\Helper\TranslationHelper;
use eZ\Publish\Core\MVC\Exception\SourceImageNotFoundException;
use eZ\Publish\Core\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\Image\Value;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use eZ\Publish\SPI\Variation\Values\Variation;
use Netgen\Bundle\OpenGraphBundle\Handler\FieldType\Image;
use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ImageTest extends HandlerBaseTest
{
    /**
     * @var Image
     */
    protected $image;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $variationHandler;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestStack;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $logger;

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

        $this->content = new Content(array('versionInfo' => new VersionInfo()));

        $this->variationHandler = $this->getMockBuilder(AliasGenerator::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getVariation'))
            ->getMock();

        $this->requestStack = $this->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getCurrentRequest'))
            ->getMock();

        $this->logger = $this->getMockBuilder(NullLogger::class)
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $this->image = new Image($this->fieldHelper, $this->translationHelper, $this->variationHandler, $this->requestStack, $this->logger);
        $this->image->setContent($this->content);

        $this->field = new Field(array('value' => new Value()));
    }

    public function testInstanceOfHandlerInterface()
    {
        $this->assertInstanceOf(HandlerInterface::class, $this->image);
    }

    /**
     * @expectedException \eZ\Publish\Core\Base\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Argument '$params[0]' is invalid: Field type handlers require at least a field identifier.
     */
    public function testGettingTagsWithoutFieldIdentifier()
    {
        $this->image->getMetaTags('some_tag', array());
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

        $this->image->getMetaTags('some_tag', array('some_value'));
    }

    /**
     * @expectedException \eZ\Publish\Core\Base\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Argument '$params[0]' is invalid: Netgen\Bundle\OpenGraphBundle\Handler\FieldType\Image field type handler does not support field with identifier ''.
     */
    public function testGettingTagsWithUnsupportedField()
    {
        $this->translationHelper->expects($this->once())
            ->method('getTranslatedField')
            ->willReturn(new Field());

        $this->image->getMetaTags('some_tag', array('some_value'));
    }

    public function testGettingTagsWithEmptyField()
    {
        $this->translationHelper->expects($this->once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $this->fieldHelper->expects($this->once())
            ->method('isFieldEmpty')
            ->willReturn(true);

        $this->image->getMetaTags('some_tag', array('some_value'));
    }

    public function testGettingTagsWithExceptionThrownByVariationHandler()
    {
        $this->translationHelper->expects($this->once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $this->requestStack->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->image->getMetaTags('some_tag', array('some_value', 'some_value_2', 'some_value_3'));
    }

    public function testGettingTags()
    {
        $this->translationHelper->expects($this->once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $this->fieldHelper->expects($this->once())
            ->method('isFieldEmpty')
            ->willReturn(false);

        $variation = new Variation(array('uri' => '/some/uri'));

        $this->variationHandler->expects($this->once())
            ->method('getVariation')
            ->willReturn($variation);

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $this->requestStack->expects($this->exactly(2))
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->image->getMetaTags('some_tag', array('some_value', 'some_value_2', 'some_value_3'));
    }

    public function testGettingTagsWithVariationServiceThrowsInvalidVariationException()
    {
        $this->translationHelper->expects($this->once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $this->fieldHelper->expects($this->once())
            ->method('isFieldEmpty')
            ->willReturn(false);

        $this->variationHandler->expects($this->once())
            ->method('getVariation')
            ->willThrowException(new InvalidVariationException('name', 'type'));

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $this->requestStack->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->logger->expects($this->once())
            ->method('error');

        $this->image->getMetaTags('some_tag', array('some_value', 'some_value_2', 'some_value_3'));
    }

    public function testGettingTagsWithVariationServiceThrowsSourceImageNotFoundException()
    {
        $this->translationHelper->expects($this->once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $this->fieldHelper->expects($this->once())
            ->method('isFieldEmpty')
            ->willReturn(false);

        $this->variationHandler->expects($this->once())
            ->method('getVariation')
            ->willThrowException(new SourceImageNotFoundException('message'));

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();

        $this->requestStack->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->logger->expects($this->once())
            ->method('error');

        $this->image->getMetaTags('some_tag', array('some_value', 'some_value_2', 'some_value_3'));
    }

    public function testGettingTagsWithMultipleArgumentsInArray()
    {
        $this->translationHelper->expects($this->once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $this->image->getMetaTags('some_tag', array('some_value', 'some_value_2'));
    }
}
