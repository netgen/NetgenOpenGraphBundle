<?php

namespace Netgen\Bundle\OpenGraphBundle\Handler;

use eZ\Publish\API\Repository\Values\Content\Content;

abstract class Handler implements HandlerInterface, ContentAware
{
    /**
     * @var \eZ\Publish\API\Repository\Values\Content\Content
     */
    protected $content;

    /**
     * Sets the content
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     */
    public function setContent( Content $content )
    {
        $this->content = $content;
    }
}
