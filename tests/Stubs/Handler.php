<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Stubs;

use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;

final class Handler implements HandlerInterface
{
    public function getMetaTags(string $tagName, array $params = []): array
    {
        return [
            new Item(
                'og:type',
                'article',
            ),
        ];
    }
}
