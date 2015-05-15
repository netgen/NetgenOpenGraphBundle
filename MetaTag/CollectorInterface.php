<?php

namespace Netgen\Bundle\OpenGraphBundle\MetaTag;

use eZ\Publish\API\Repository\Values\Content\Content;

interface CollectorInterface
{
    /**
     * Collects meta tags from all handlers registered for provided content
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return \Netgen\Bundle\OpenGraphBundle\MetaTag\Item[]
     */
    public function collect( Content $content );
}
