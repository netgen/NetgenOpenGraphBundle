<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\MetaTag;

interface RendererInterface
{
    /**
     * Renders provided meta tags.
     *
     * @param \Netgen\Bundle\OpenGraphBundle\MetaTag\Item[]
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException If meta tag is not an instance of
     *         \Netgen\Bundle\OpenGraphBundle\MetaTag\Item
     *
     * @return string
     */
    public function render(array $metaTags = []): string;
}
