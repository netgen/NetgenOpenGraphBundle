<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\MetaTag;

use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;

class ItemTest extends \PHPUnit_Framework_TestCase
{
    public function testItemGettersAndConstruct()
    {
        $item = new Item('name', 'value');

        $this->assertEquals('name', $item->getTagName());
        $this->assertEquals('value', $item->getTagValue());
    }
}
