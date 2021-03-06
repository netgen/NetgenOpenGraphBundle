<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Exception;

use Netgen\Bundle\OpenGraphBundle\Exception\HandlerNotFoundException;
use PHPUnit\Framework\TestCase;

final class HandlerNotFoundExceptionTest extends TestCase
{
    public function testExceptionThrow(): void
    {
        $this->expectException(HandlerNotFoundException::class);
        $this->expectExceptionMessage("Meta tag handler with 'test' identifier not found.");

        throw new HandlerNotFoundException('test');
    }
}
