<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler\Literal;

use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use Netgen\Bundle\OpenGraphBundle\Handler\Literal\Url;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class UrlTest extends TestCase
{
    /**
     * @var Url
     */
    protected $url;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected $requestStack;

    public function setUp()
    {
        $this->requestStack = new RequestStack();
        $this->url = new Url($this->requestStack);
    }

    public function testInstanceOfHandlerInterface()
    {
        $this->assertInstanceOf(HandlerInterface::class, $this->url);
    }

    /**
     * @expectedException \eZ\Publish\Core\Base\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Argument '$params[0]' is invalid: Literal URL handler requires the path to output.
     */
    public function testGettingTagsWithEmptyParams()
    {
        $this->url->getMetaTags('some_tag', array());
    }

    /**
     * @dataProvider validResultProvider
     *
     * @param string $input
     * @param string $output
     */
    public function testGettingTagsWithValidResult($input, $output)
    {
        $request = Request::create('https://domain.com');

        $this->requestStack->push($request);

        $result = $this->url->getMetaTags('some_tag', array($input));

        $this->assertInternalType('array', $result);
        $this->assertInstanceOf(Item::class, $result[0]);
        $this->assertEquals('some_tag', $result[0]->getTagName());
        $this->assertEquals($output, $result[0]->getTagValue());
    }

    public function validResultProvider()
    {
        return array(
            array('https://other.domain.com/some/path', 'https://other.domain.com/some/path'),
            array('http://other.domain.com/some/path', 'http://other.domain.com/some/path'),
            array('/some/path', 'https://domain.com/some/path'),
            array('some/path', 'https://domain.com/some/path'),
            array('/', 'https://domain.com/'),
            array('', 'https://domain.com/'),
        );
    }

    /**
     * @dataProvider validResultProviderWithoutRequest
     *
     * @param string $input
     * @param string $output
     */
    public function testGettingTagsWithValidResultAndWithoutRequest($input, $output)
    {
        $result = $this->url->getMetaTags('some_tag', array($input));

        $this->assertInternalType('array', $result);
        $this->assertInstanceOf(Item::class, $result[0]);
        $this->assertEquals('some_tag', $result[0]->getTagName());
        $this->assertEquals($output, $result[0]->getTagValue());
    }

    public function validResultProviderWithoutRequest()
    {
        return array(
            array('https://other.domain.com/some/path', 'https://other.domain.com/some/path'),
            array('http://other.domain.com/some/path', 'http://other.domain.com/some/path'),
            array('/some/path', '/some/path'),
            array('some/path', 'some/path'),
            array('/', '/'),
            array('', ''),
        );
    }
}
