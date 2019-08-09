<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Exception;

use Exception;

final class HandlerNotFoundException extends Exception
{
    public function __construct(string $handlerIdentifier)
    {
        parent::__construct("Meta tag handler with '{$handlerIdentifier}' identifier not found.");
    }
}
