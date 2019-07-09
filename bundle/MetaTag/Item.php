<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\MetaTag;

class Item
{
    /**
     * @var string
     */
    protected $tagName;

    /**
     * @var string
     */
    protected $tagValue;

    public function __construct(string $tagName, string $tagValue)
    {
        $this->tagName = $tagName;
        $this->tagValue = $tagValue;
    }

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
