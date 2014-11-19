<?php

namespace Netgen\Bundle\OpenGraphBundle\Value;

class MetaTag
{
    /**
     * @var string
     */
    protected $tagName;

    /**
     * @var string
     */
    protected $tagValue;

    /**
     * Constructor
     *
     * @param string $tagName
     * @param string $tagValue
     */
    public function __construct( $tagName, $tagValue )
    {
        $this->tagName = $tagName;
        $this->tagValue = $tagValue;
    }

    /**
     * Returns tag name
     *
     * @return string
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * Returns tag value
     *
     * @return string
     */
    public function getTagValue()
    {
        return $this->tagValue;
    }
}
