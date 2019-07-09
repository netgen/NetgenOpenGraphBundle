<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler;

use eZ\Publish\API\Repository\Values\Content\Content;

interface ContentAware
{
    /**
     * Sets the content.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     */
    public function setContent(Content $content);
}
