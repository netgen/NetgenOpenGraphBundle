<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\MetaTag;

use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testItemGettersAndConstruct(): void
    {
        $item = new Item('name', 'value');

        self::assertSame('name', $item->getTagName());
        self::assertSame('value', $item->getTagValue());
    }
}
