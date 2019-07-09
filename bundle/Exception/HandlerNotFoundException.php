<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Exception;

use Exception;

class HandlerNotFoundException extends Exception
{
    /**
     * Constructor.
     *
     * @param string $handlerIdentifier
     */
    public function __construct($handlerIdentifier)
    {
        parent::__construct("Meta tag handler with '{$handlerIdentifier}' identifier not found.");
    }
}
