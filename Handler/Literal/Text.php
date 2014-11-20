<?php

namespace Netgen\Bundle\OpenGraphBundle\Handler\Literal;

use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;

class Text implements HandlerInterface
{
    /**
     * Returns the array of meta tags
     *
     * @param array $params
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException If number of params is incorrect
     *
     * @return \Netgen\Bundle\OpenGraphBundle\MetaTag\Item[]
     */
    public function getMetaTags( array $params = array() )
    {
        if ( count( $params ) < 2 )
        {
            throw new InvalidArgumentException(
                '$params',
                'Literal text handler requires at least two parameters, meta tag name and text.'
            );
        }

        return array(
            new Item(
                $params[0],
                $params[1]
            )
        );
    }
}
