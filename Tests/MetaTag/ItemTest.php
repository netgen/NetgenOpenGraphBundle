<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\MetaTag;

use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testItemGettersAndConstruct()
    {
        $item = new Item('name', 'value');

        $this->assertEquals('name', $item->getTagName());
        $this->assertEquals('value', $item->getTagValue());
    }
}
