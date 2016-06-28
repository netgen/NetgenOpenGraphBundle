<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\Exception;

use Netgen\Bundle\OpenGraphBundle\Exception\HandlerNotFoundException;

class HandlerNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
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
