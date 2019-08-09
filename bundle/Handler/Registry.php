<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler;

use Netgen\Bundle\OpenGraphBundle\Exception\HandlerNotFoundException;

final class Registry
{
    /**
     * @var \Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface[]
     */
    private $metaTagHandlers = [];

    /**
     * Adds a handler to the registry.
     */
    public function addHandler(string $identifier, HandlerInterface $metaTagHandler): void
    {
        if (!isset($this->metaTagHandlers[$identifier])) {
            $this->metaTagHandlers[$identifier] = $metaTagHandler;
        }
    }

    /**
     * Returns handler by its identifier.
     *
     * @throws \Netgen\Bundle\OpenGraphBundle\Exception\HandlerNotFoundException If the handler is not found
     */
    public function getHandler(string $identifier): HandlerInterface
    {
        if (isset($this->metaTagHandlers[$identifier])) {
            return $this->metaTagHandlers[$identifier];
        }

        throw new HandlerNotFoundException($identifier);
    }
}
