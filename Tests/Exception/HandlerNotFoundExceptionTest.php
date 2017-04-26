<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\Exception;

use Netgen\Bundle\OpenGraphBundle\Exception\HandlerNotFoundException;
use PHPUnit\Framework\TestCase;

class HandlerNotFoundExceptionTest extends TestCase
{
    /**
     * @expectedException \Netgen\Bundle\OpenGraphBundle\Exception\HandlerNotFoundException
     * @expectedExceptionMessage Meta tag handler with 'test' identifier not found.
     */
    public function testExceptionThrow()
    {
        throw new HandlerNotFoundException('test');
    }
}
