<?php

namespace Netgen\Bundle\OpenGraphBundle\Handler\Literal;

use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;

class Text implements HandlerInterface
{
    /**
     * Returns the array of meta tags.
     *
     * @param string $tagName
     * @param array $params
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException If number of params is incorrect
     *
     * @return \Netgen\Bundle\OpenGraphBundle\MetaTag\Item[]
     */
    public function getMetaTags($tagName, array $params = array())
    {
        if (!isset($params[0])) {
            throw new InvalidArgumentException(
                '$params[0]',
                'Literal text handler requires the text to output.'
            );
        }

        return array(
            new Item(
                $tagName,
                $params[0]
            ),
        );
    }
}
