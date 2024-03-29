<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\MetaTag;

use Ibexa\Contracts\Core\Repository\Values\Content\Content;

interface CollectorInterface
{
    /**
     * Collects meta tags from all handlers registered for provided content.
     *
     * @return \Netgen\Bundle\OpenGraphBundle\MetaTag\Item[]
     */
    public function collect(Content $content): array;
}
