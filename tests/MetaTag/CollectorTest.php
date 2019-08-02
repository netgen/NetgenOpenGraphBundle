<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\MetaTag;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\ConfigResolver;
use eZ\Publish\Core\Repository\ContentTypeService;
use eZ\Publish\Core\Repository\Values\Content\Content;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use eZ\Publish\Core\Repository\Values\ContentType\ContentType;
use eZ\Publish\Core\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\SPI\Persistence\Content\ContentInfo;
use LogicException;
use Netgen\Bundle\OpenGraphBundle\Handler\FieldType\TextLine;
use Netgen\Bundle\OpenGraphBundle\Handler\Registry;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Collector;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use PHPUnit\Framework\TestCase;
use stdClass;

class CollectorTest extends TestCase
{
    /**
     * @var Collector
     */
    protected $collector;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $registry;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $contentTypeService;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $config;

    protected function setUp(): void
    {
        $this->registry = $this->getMockBuilder(Registry::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getHandler'])
            ->getMock();

        $this->contentTypeService = $this->getMockBuilder(ContentTypeService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['loadContentType'])
            ->getMock();

        $this->config = $this->getMockBuilder(ConfigResolver::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['hasParameter', 'getParameter'])
            ->getMock();

        $this->collector = new Collector($this->registry, $this->contentTypeService, $this->config);
    }

    public function testCollect(): void
    {
        $this->config->expects(self::at(0))
            ->method('hasParameter')
            ->willReturn(true);

        $this->config->expects(self::at(1))
            ->method('getParameter')
            ->willReturn([]);

        $this->config->expects(self::at(2))
            ->method('hasParameter')
            ->willReturn(true);

        $handlers = [
            'article' => [
                [
                    'handler' => 'literal/text',
                    'tag' => 'og:type',
                    'params' => [
                        'article',
                    ],
                ],
            ],
        ];

        $this->config->expects(self::at(3))
            ->method('getParameter')
            ->willReturn($handlers);

        $versionInfo = new VersionInfo(
            [
                'contentInfo' => new ContentInfo(
                    [
                        'id' => 123,
                    ]
                ),
            ]
        );

        $content = new Content(
            [
                'versionInfo' => $versionInfo,
            ]
        );

        $contentType = new ContentType(
            [
                'id' => 123,
                'identifier' => 'article',
                'fieldDefinitions' => [
                    new FieldDefinition(
                        [
                            'id' => 'id',
                            'identifier' => 'name',
                            'fieldTypeIdentifier' => 'eztext',
                        ]
                    ),
                ],
            ]
        );

        $this->contentTypeService->expects(self::once())
            ->method('loadContentType')
            ->willReturn($contentType);

        $handler = $this->getMockBuilder(TextLine::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getMetaTags'])
            ->getMock();

        $items = [
            new Item(
                'og:type',
                'article'
            ),
        ];

        $handler->expects(self::once())
            ->method('getMetaTags')
            ->willReturn($items);

        $this->registry->expects(self::once())
            ->method('getHandler')
            ->willReturn($handler);

        $this->collector->collect($content);
    }

    public function testCollectWithLogicException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("'literal/text' handler returned wrong value. Expected 'Netgen\\Bundle\\OpenGraphBundle\\MetaTag\\Item', got 'stdClass'.");

        $handlerArray = [
            [
                'handler' => 'literal/text',
                'tag' => 'og:type',
                'params' => [
                    'article',
                ],
            ],
        ];

        $handlers = ['all_content_types' => $handlerArray];

        $this->config->expects(self::at(0))
            ->method('hasParameter')
            ->willReturn(true);

        $this->config->expects(self::at(1))
            ->method('getParameter')
            ->willReturn($handlers);

        $this->config->expects(self::at(2))
            ->method('hasParameter')
            ->willReturn(false);

        $versionInfo = new VersionInfo(
            [
                'contentInfo' => new ContentInfo(
                    [
                        'id' => 123,
                    ]
                ),
            ]
        );

        $content = new Content(
            [
                'versionInfo' => $versionInfo,
            ]
        );

        $contentType = new ContentType(
            [
                'id' => 123,
                'identifier' => 'article',
                'fieldDefinitions' => [
                    new FieldDefinition(
                        [
                            'id' => 'id',
                            'identifier' => 'name',
                            'fieldTypeIdentifier' => 'eztext',
                        ]
                    ),
                ],
            ]
        );

        $this->contentTypeService->expects(self::once())
            ->method('loadContentType')
            ->willReturn($contentType);

        $handler = $this->getMockBuilder(TextLine::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getMetaTags'])
            ->getMock();

        $handler->expects(self::once())
            ->method('getMetaTags')
            ->willReturn([new stdClass()]);

        $this->registry->expects(self::once())
            ->method('getHandler')
            ->willReturn($handler);

        $this->collector->collect($content);
    }
}
