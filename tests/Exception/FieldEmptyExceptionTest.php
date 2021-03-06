<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Exception;

use Netgen\Bundle\OpenGraphBundle\Exception\FieldEmptyException;
use PHPUnit\Framework\TestCase;

final class FieldEmptyExceptionTest extends TestCase
{
    public function testExceptionThrow(): void
    {
        $this->expectException(FieldEmptyException::class);
        $this->expectExceptionMessage("Field with identifier 'test' has empty value.");

        throw new FieldEmptyException('test');
    }
}
