<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler;

use Netgen\Bundle\OpenGraphBundle\Exception\HandlerNotFoundException;
use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use Netgen\Bundle\OpenGraphBundle\Handler\Registry;
use PHPUnit\Framework\TestCase;

final class RegistryTest extends TestCase
{
    private Registry $registry;

    protected function setUp(): void
    {
        $this->registry = new Registry();
    }

    public function testAddingHandlers(): void
    {
        $handler = $this->createStub(HandlerInterface::class);
        $this->registry->addHandler('some_handler', $handler);

        self::assertSame($this->registry->getHandler('some_handler'), $handler);
    }

    public function testGettingHandlers(): void
    {
        $handler = $this->createStub(HandlerInterface::class);
        $this->registry->addHandler('some_handler', $handler);

        $returnedHandler = $this->registry->getHandler('some_handler');

        self::assertSame($handler, $returnedHandler);
    }

    public function testGettingNonExistentHandler(): void
    {
        $this->expectException(HandlerNotFoundException::class);
        $this->expectExceptionMessage("Meta tag handler with 'some_handler' identifier not found.");

        $this->registry->getHandler('some_handler');
    }
}
