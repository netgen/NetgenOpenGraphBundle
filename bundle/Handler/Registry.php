<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler;

use Netgen\Bundle\OpenGraphBundle\Exception\HandlerNotFoundException;

class Registry
{
    /**
     * @var \Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface[]
     */
    protected $metaTagHandlers = [];

    /**
     * Adds a handler to the registry.
     *
     * @param string $identifier
     * @param \Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface $metaTagHandler
     */
    public function addHandler($identifier, HandlerInterface $metaTagHandler)
    {
        if (!isset($this->metaTagHandlers[$identifier])) {
            $this->metaTagHandlers[$identifier] = $metaTagHandler;
        }
    }

    /**
     * Returns handler by its identifier.
     *
     * @param string $identifier
     *
     * @throws \Netgen\Bundle\OpenGraphBundle\Exception\HandlerNotFoundException If the handler is not found
     *
     * @return \Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface
     */
    public function getHandler($identifier)
    {
        if (isset($this->metaTagHandlers[$identifier])) {
            return $this->metaTagHandlers[$identifier];
        }

        throw new HandlerNotFoundException($identifier);
    }
}
