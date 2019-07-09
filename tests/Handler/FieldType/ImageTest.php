<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler\FieldType;

use eZ\Bundle\EzPublishCoreBundle\Imagine\AliasGenerator;
use eZ\Publish\API\Repository\Exceptions\InvalidArgumentException;
use eZ\Publish\API\Repository\Exceptions\InvalidVariationException;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\Image\Value;
use eZ\Publish\Core\Helper\FieldHelper;
use eZ\Publish\Core\Helper\TranslationHelper;
use eZ\Publish\Core\MVC\Exception\SourceImageNotFoundException;
use eZ\Publish\Core\Repository\Values\Content\Content;
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
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $variationHandler;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestStack;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $logger;

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

        $this->content = new Content(['versionInfo' => new VersionInfo()]);

        $this->variationHandler = $this->getMockBuilder(AliasGenerator::class)
            ->disableOriginalConstructor()
            ->setMethods(['getVariation'])
            ->getMock();

        $this->requestStack = $this->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCurrentRequest'])
            ->getMock();

        $this->logger = $this->getMockBuilder(NullLogger::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $this->image = new Image($this->fieldHelper, $this->translationHelper, $this->variationHandler, $this->requestStack, $this->logger);
        $this->image->setContent($this->content);

        $this->field = new Field(['value' => new Value()]);
    }

    public function testInstanceOfHandlerInterface(): void
    {
        self::assertInstanceOf(HandlerInterface::class, $this->image);
    }

    public function testGettingTagsWithoutFieldIdentifier(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument '\$params[0]' is invalid: Field type handlers require at least a field identifier.");

        $this->image->getMetaTags('some_tag');
    }

    public function testGettingTagsWithNonExistentField(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument '\$params[0]' is invalid: Field 'some_value' does not exist in content.");

        $this->translationHelper->expects(self::once())
            ->method('getTranslatedField')
            ->willReturn(null);

        $this->image->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithUnsupportedField(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument '\$params[0]' is invalid: Netgen\\Bundle\\OpenGraphBundle\\Handler\\FieldType\\Image field type handler does not support field with identifier ''.");

        $this->translationHelper->expects(self::once())
            ->method('getTranslatedField')
            ->willReturn(new Field());

        $this->image->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithEmptyField(): void
    {
        $this->translationHelper->expects(self::once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $this->fieldHelper->expects(self::once())
            ->method('isFieldEmpty')
            ->willReturn(true);

        $this->image->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithExceptionThrownByVariationHandler(): void
    {
        $this->translationHelper->expects(self::once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $this->requestStack->expects(self::once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->image->getMetaTags('some_tag', ['some_value', 'some_value_2', 'some_value_3']);
    }

    public function testGettingTags(): void
    {
        $this->translationHelper->expects(self::once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $this->fieldHelper->expects(self::once())
            ->method('isFieldEmpty')
            ->willReturn(false);

        $variation = new Variation(['uri' => '/some/uri']);

        $this->variationHandler->expects(self::once())
            ->method('getVariation')
            ->willReturn($variation);

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $this->requestStack->expects(self::exactly(2))
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->image->getMetaTags('some_tag', ['some_value', 'some_value_2', 'some_value_3']);
    }

    public function testGettingTagsWithVariationServiceThrowsInvalidVariationException(): void
    {
        $this->translationHelper->expects(self::once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $this->fieldHelper->expects(self::once())
            ->method('isFieldEmpty')
            ->willReturn(false);

        $this->variationHandler->expects(self::once())
            ->method('getVariation')
            ->willThrowException(new InvalidVariationException('name', 'type'));

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $this->requestStack->expects(self::once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->logger->expects(self::once())
            ->method('error');

        $this->image->getMetaTags('some_tag', ['some_value', 'some_value_2', 'some_value_3']);
    }

    public function testGettingTagsWithVariationServiceThrowsSourceImageNotFoundException(): void
    {
        $this->translationHelper->expects(self::once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $this->fieldHelper->expects(self::once())
            ->method('isFieldEmpty')
            ->willReturn(false);

        $this->variationHandler->expects(self::once())
            ->method('getVariation')
            ->willThrowException(new SourceImageNotFoundException('message'));

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $this->requestStack->expects(self::once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->logger->expects(self::once())
            ->method('error');

        $this->image->getMetaTags('some_tag', ['some_value', 'some_value_2', 'some_value_3']);
    }

    public function testGettingTagsWithMultipleArgumentsInArray(): void
    {
        $this->translationHelper->expects(self::once())
            ->method('getTranslatedField')
            ->willReturn($this->field);

        $this->image->getMetaTags('some_tag', ['some_value', 'some_value_2']);
    }
}
