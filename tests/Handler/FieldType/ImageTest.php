<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler\FieldType;

use Ibexa\Bundle\Core\Imagine\AliasGenerator;
use Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException;
use Ibexa\Contracts\Core\Repository\Exceptions\InvalidVariationException;
use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Ibexa\Core\FieldType\Image\Value;
use Ibexa\Core\Helper\FieldHelper;
use Ibexa\Core\MVC\Exception\SourceImageNotFoundException;
use Ibexa\Core\Repository\Values\Content\Content;
use Ibexa\Core\Repository\Values\Content\VersionInfo;
use Ibexa\Contracts\Core\Variation\Values\Variation;
use Netgen\Bundle\OpenGraphBundle\Handler\FieldType\Image;
use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ImageTest extends TestCase
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
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\Field
     */
    private $field;

    /**
     * @var \Netgen\Bundle\OpenGraphBundle\Handler\FieldType\Image
     */
    private $image;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $variationHandler;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $requestStack;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $logger;

    protected function setUp(): void
    {
        $this->fieldHelper = $this->getMockBuilder(FieldHelper::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isFieldEmpty'])
            ->getMock();

        $this->content = $this->getMockBuilder(Content::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->content->expects(self::any())
            ->method('getVersionInfo')
            ->willReturn(new VersionInfo());

        $this->variationHandler = $this->getMockBuilder(AliasGenerator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getVariation'])
            ->getMock();

        $this->requestStack = $this->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getCurrentRequest'])
            ->getMock();

        $this->logger = $this->getMockBuilder(NullLogger::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->image = new Image($this->fieldHelper, $this->variationHandler, $this->requestStack, $this->logger);
        $this->image->setContent($this->content);

        $this->field = new Field(['value' => new Value(), 'fieldDefIdentifier' => 'field']);
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

        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn(null);

        $this->image->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithUnsupportedField(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument '\$params[0]' is invalid: Netgen\\Bundle\\OpenGraphBundle\\Handler\\FieldType\\Image field type handler does not support field with identifier 'field'.");

        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn(new Field(['fieldDefIdentifier' => 'field']));

        $this->image->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithEmptyField(): void
    {
        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn($this->field);

        $this->fieldHelper->expects(self::once())
            ->method('isFieldEmpty')
            ->willReturn(true);

        $this->image->getMetaTags('some_tag', ['some_value']);
    }

    public function testGettingTagsWithExceptionThrownByVariationHandler(): void
    {
        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn($this->field);

        $request = Request::create('/');

        $this->requestStack->expects(self::once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->image->getMetaTags('some_tag', ['some_value', 'some_value_2', 'some_value_3']);
    }

    public function testGettingTags(): void
    {
        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn($this->field);

        $this->fieldHelper->expects(self::once())
            ->method('isFieldEmpty')
            ->willReturn(false);

        $variation = new Variation(['uri' => '/some/uri']);

        $this->variationHandler->expects(self::once())
            ->method('getVariation')
            ->willReturn($variation);

        $request = Request::create('/');

        $this->requestStack->expects(self::exactly(2))
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->image->getMetaTags('some_tag', ['some_value', 'some_value_2', 'some_value_3']);
    }

    public function testGettingTagsWithVariationServiceThrowsInvalidVariationException(): void
    {
        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn($this->field);

        $this->fieldHelper->expects(self::once())
            ->method('isFieldEmpty')
            ->willReturn(false);

        $this->variationHandler->expects(self::once())
            ->method('getVariation')
            ->willThrowException(new InvalidVariationException('name', 'type'));

        $request = Request::create('/');

        $this->requestStack->expects(self::once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->logger->expects(self::once())
            ->method('error');

        $this->image->getMetaTags('some_tag', ['some_value', 'some_value_2', 'some_value_3']);
    }

    public function testGettingTagsWithVariationServiceThrowsSourceImageNotFoundException(): void
    {
        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn($this->field);

        $this->fieldHelper->expects(self::once())
            ->method('isFieldEmpty')
            ->willReturn(false);

        $this->variationHandler->expects(self::once())
            ->method('getVariation')
            ->willThrowException(new SourceImageNotFoundException('message'));

        $request = Request::create('/');

        $this->requestStack->expects(self::once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->logger->expects(self::once())
            ->method('error');

        $this->image->getMetaTags('some_tag', ['some_value', 'some_value_2', 'some_value_3']);
    }

    public function testGettingTagsWithMultipleArgumentsInArray(): void
    {
        $this->content->expects(self::once())
            ->method('getField')
            ->willReturn($this->field);

        $this->image->getMetaTags('some_tag', ['some_value', 'some_value_2']);
    }
}
