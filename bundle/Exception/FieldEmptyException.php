<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Exception;

use Exception;

final class FieldEmptyException extends Exception
{
    public function __construct(string $fieldIdentifier)
    {
        parent::__construct("Field with identifier '{$fieldIdentifier}' has empty value.");
    }
}
