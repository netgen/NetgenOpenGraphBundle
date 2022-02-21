<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler\Literal;

use Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException;
use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use Netgen\Bundle\OpenGraphBundle\Handler\Literal\Url;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class UrlTest extends TestCase
{
    /**
     * @var Url
     */
    private $url;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    protected function setUp(): void
    {
        $this->requestStack = new RequestStack();
        $this->url = new Url($this->requestStack);
    }

    public function testInstanceOfHandlerInterface(): void
    {
        self::assertInstanceOf(HandlerInterface::class, $this->url);
    }

    public function testGettingTagsWithEmptyParams(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Argument '\$params[0]' is invalid: Literal URL handler requires the path to output.");

        $this->url->getMetaTags('some_tag');
    }

    /**
     * @dataProvider validResultProvider
     */
    public function testGettingTagsWithValidResult(string $input, string $output): void
    {
        $request = Request::create('https://domain.com');

        $this->requestStack->push($request);

        $result = $this->url->getMetaTags('some_tag', [$input]);

        self::assertIsArray($result);
        self::assertInstanceOf(Item::class, $result[0]);
        self::assertSame('some_tag', $result[0]->getTagName());
        self::assertSame($output, $result[0]->getTagValue());
    }

    public function validResultProvider(): array
    {
        return [
            ['https://other.domain.com/some/path', 'https://other.domain.com/some/path'],
            ['http://other.domain.com/some/path', 'http://other.domain.com/some/path'],
            ['/some/path', 'https://domain.com/some/path'],
            ['some/path', 'https://domain.com/some/path'],
            ['/', 'https://domain.com/'],
            ['', 'https://domain.com/'],
        ];
    }

    /**
     * @dataProvider validResultProviderWithoutRequest
     */
    public function testGettingTagsWithValidResultAndWithoutRequest(string $input, string $output): void
    {
        $result = $this->url->getMetaTags('some_tag', [$input]);

        self::assertIsArray($result);
        self::assertInstanceOf(Item::class, $result[0]);
        self::assertSame('some_tag', $result[0]->getTagName());
        self::assertSame($output, $result[0]->getTagValue());
    }

    public function validResultProviderWithoutRequest(): array
    {
        return [
            ['https://other.domain.com/some/path', 'https://other.domain.com/some/path'],
            ['http://other.domain.com/some/path', 'http://other.domain.com/some/path'],
            ['/some/path', '/some/path'],
            ['some/path', 'some/path'],
            ['/', '/'],
            ['', ''],
        ];
    }
}
