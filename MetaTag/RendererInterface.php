<?php

namespace Netgen\Bundle\OpenGraphBundle\MetaTag;

interface RendererInterface
{
    /**
     * Renders provided meta tags
     *
     * @param \Netgen\Bundle\OpenGraphBundle\MetaTag\Item[]
     *
     * @return string
     */
    public function render( array $metaTags = array() );
}
