<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\MetaTag;

final class Item
{
    public function __construct(
        private readonly string $tagName,
        private readonly string $tagValue,
    ) {}

    /**
     * Returns tag name.
     */
    public function getTagName(): string
    {
        return $this->tagName;
    }

    /**
     * Returns tag value.
     */
    public function getTagValue(): string
    {
        return $this->tagValue;
    }
}
