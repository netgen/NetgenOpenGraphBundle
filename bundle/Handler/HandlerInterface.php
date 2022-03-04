<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler;

interface HandlerInterface
{
    /**
     * Returns the array of meta tags.
     *
     * @param array<string, mixed> $params
     *
     * @return \Netgen\Bundle\OpenGraphBundle\MetaTag\Item[]
     */
    public function getMetaTags(string $tagName, array $params = []): array;
}
