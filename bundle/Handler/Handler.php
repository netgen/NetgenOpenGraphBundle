<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler;

use Ibexa\Contracts\Core\Repository\Values\Content\Content;

abstract class Handler implements HandlerInterface, ContentAware
{
    /**
     * @var \Ibexa\Contracts\Core\Repository\Values\Content\Content
     */
    protected $content;

    public function setContent(Content $content): void
    {
        $this->content = $content;
    }
}
