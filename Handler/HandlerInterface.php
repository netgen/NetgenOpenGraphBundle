<?php

namespace Netgen\Bundle\OpenGraphBundle\Handler;

interface HandlerInterface
{
    /**
     * Returns the array of meta tags
     *
     * @param array $params
     *
     * @return \Netgen\Bundle\OpenGraphBundle\MetaTag\Item[]
     */
    public function getMetaTags( array $params = array() );
}
