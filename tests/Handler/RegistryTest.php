<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler;

use Netgen\Bundle\OpenGraphBundle\Exception\HandlerNotFoundException;
use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use Netgen\Bundle\OpenGraphBundle\Handler\Registry;
use PHPUnit\Framework\TestCase;

class RegistryTest extends TestCase
{
    /**
     * @var Registry
     */
    protected $registry;

    public function setUp(): void
    {
        $this->registry = new Registry();
    }

    public function testAddingHandlers()
    {
        $handler = $this->getMockForAbstractClass(HandlerInterface::class);
        $this->registry->addHandler('some_handler', $handler);

        $this->assertEquals($this->registry->getHandler('some_handler'), $handler);
    }

    public function testGettingHandlers()
    {
        $handler = $this->getMockForAbstractClass(HandlerInterface::class);
        $this->registry->addHandler('some_handler', $handler);

        $returnedHandler = $this->registry->getHandler('some_handler');

        $this->assertSame($handler, $returnedHandler);
    }

    public function testGettingNonExistentHandler()
    {
        $this->expectException(HandlerNotFoundException::class);
        $this->expectExceptionMessage("Meta tag handler with 'some_handler' identifier not found.");

        $this->registry->getHandler('some_handler');
    }
}
