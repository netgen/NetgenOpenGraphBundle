<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\Exception;

use Netgen\Bundle\OpenGraphBundle\Exception\FieldEmptyException;

class FieldEmptyExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Netgen\Bundle\OpenGraphBundle\Exception\FieldEmptyException
     * @expectedExceptionMessage Field with identifier 'test' has empty value.
     */
    public function testExceptionThrow()
    {
        throw new FieldEmptyException('test');
    }
}
