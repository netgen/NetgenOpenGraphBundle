<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler\Literal;

use Ibexa\Core\Base\Exceptions\InvalidArgumentException;
use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;

final class Text implements HandlerInterface
{
    public function getMetaTags(string $tagName, array $params = []): array
    {
        if (!isset($params[0])) {
            throw new InvalidArgumentException(
                '$params[0]',
                'Literal text handler requires the text to output.',
            );
        }

        return [
            new Item(
                $tagName,
                $params[0],
            ),
        ];
    }
}
