<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler;

use Ibexa\Contracts\Core\Repository\Values\Content\Content;

interface ContentAware
{
    /**
     * Sets the content.
     *
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Content $content
     */
    public function setContent(Content $content): void;
}
