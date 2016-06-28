<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\MetaTag;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\ConfigResolver;
use eZ\Publish\Core\Repository\ContentTypeService;
use Netgen\Bundle\OpenGraphBundle\Handler\Registry;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Collector;
use Netgen\Bundle\OpenGraphBundle\MetaTag\CollectorInterface;

class CollectorTest extends \PHPUnit_Framework_TestCase
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
}
