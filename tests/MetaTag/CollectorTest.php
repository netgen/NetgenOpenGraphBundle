<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\MetaTag;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\ConfigResolver;
use eZ\Publish\Core\Repository\ContentTypeService;
use eZ\Publish\Core\Repository\Values\Content\Content;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use eZ\Publish\Core\Repository\Values\ContentType\ContentType;
use eZ\Publish\Core\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\SPI\Persistence\Content\ContentInfo;
use Netgen\Bundle\OpenGraphBundle\Handler\FieldType\TextLine;
use Netgen\Bundle\OpenGraphBundle\Handler\Registry;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Collector;
use Netgen\Bundle\OpenGraphBundle\MetaTag\CollectorInterface;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use PHPUnit\Framework\TestCase;

class CollectorTest extends TestCase
{
    /**
     * @var Collector
     */
    protected $collector;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $registry;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $contentTypeService;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $config;

    public function setUp()
    {
        $this->registry = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getHandler'))
            ->getMock();

        $this->contentTypeService = $this->getMockBuilder(ContentTypeService::class)
            ->disableOriginalConstructor()
            ->setMethods(array('loadContentType'))
            ->getMock();

        $this->config = $this->getMockBuilder(ConfigResolver::class)
            ->disableOriginalConstructor()
            ->setMethods(array('hasParameter', 'getParameter'))
            ->getMock();

        $this->collector = new Collector($this->registry, $this->contentTypeService, $this->config);
    }

    public function testInstanceOfCollectorInterface()
    {
        $this->assertInstanceOf(CollectorInterface::class, $this->collector);
    }

    public function testCollect()
    {
        $this->config->expects($this->at(0))
            ->method('hasParameter')
            ->willReturn(true);

        $this->config->expects($this->at(1))
            ->method('getParameter')
            ->willReturn(array());

        $this->config->expects($this->at(2))
            ->method('hasParameter')
            ->willReturn(true);

        $handlers = array(
            'article' => array(
                array(
                    'handler' => 'literal/text',
                    'tag' => 'og:type',
                    'params' => array(
                        'article',
                    ),
                ),
            ),
        );

        $this->config->expects($this->at(3))
            ->method('getParameter')
            ->willReturn($handlers);

        $versionInfo = new VersionInfo(
            array(
                'contentInfo' => new ContentInfo(
                    array(
                        'id' => 123,
                    )
                ),
            )
        );

        $content = new Content(
            array(
                'versionInfo' => $versionInfo,
            )
        );

        $contentType = new ContentType(
            array(
                'id' => 123,
                'identifier' => 'article',
                'fieldDefinitions' => array(
                    new FieldDefinition(
                        array(
                            'id' => 'id',
                            'identifier' => 'name',
                            'fieldTypeIdentifier' => 'eztext',
                        )
                    ),
                ),
            )
        );

        $this->contentTypeService->expects($this->once())
            ->method('loadContentType')
            ->willReturn($contentType);

        $handler = $this->getMockBuilder(TextLine::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getMetaTags'))
            ->getMock();

        $items = array(
            new Item(
                'og:type',
                array('article')
            ),
        );

        $handler->expects($this->once())
            ->method('getMetaTags')
            ->willReturn($items);

        $this->registry->expects($this->once())
            ->method('getHandler')
            ->willReturn($handler);

        $this->collector->collect($content);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage 'literal/text' handler returned wrong value. Expected 'Netgen\Bundle\OpenGraphBundle\MetaTag\Item', got 'stdClass'.
     */
    public function testCollectWithLogicException()
    {
        $handlerArray = array(
            array(
                'handler' => 'literal/text',
                'tag' => 'og:type',
                'params' => array(
                    'article',
                ),
            ),
        );

        $handlers = array('all_content_types' => $handlerArray);

        $this->config->expects($this->at(0))
            ->method('hasParameter')
            ->willReturn(true);

        $this->config->expects($this->at(1))
            ->method('getParameter')
            ->willReturn($handlers);

        $this->config->expects($this->at(2))
            ->method('hasParameter')
            ->willReturn(false);

        $versionInfo = new VersionInfo(
            array(
                'contentInfo' => new ContentInfo(
                    array(
                        'id' => 123,
                    )
                ),
            )
        );

        $content = new Content(
            array(
                'versionInfo' => $versionInfo,
            )
        );

        $contentType = new ContentType(
            array(
                'id' => 123,
                'identifier' => 'article',
                'fieldDefinitions' => array(
                    new FieldDefinition(
                        array(
                            'id' => 'id',
                            'identifier' => 'name',
                            'fieldTypeIdentifier' => 'eztext',
                        )
                    ),
                ),
            )
        );

        $this->contentTypeService->expects($this->once())
            ->method('loadContentType')
            ->willReturn($contentType);

        $handler = $this->getMockBuilder(TextLine::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getMetaTags'))
            ->getMock();

        $handler->expects($this->once())
            ->method('getMetaTags')
            ->willReturn(array(new \stdClass()));

        $this->registry->expects($this->once())
            ->method('getHandler')
            ->willReturn($handler);

        $this->collector->collect($content);
    }
}
