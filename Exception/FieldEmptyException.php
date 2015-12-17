<?php

namespace Netgen\Bundle\OpenGraphBundle\Exception;

use Exception;

class FieldEmptyException extends Exception
{
    /**
     * Constructor.
     *
     * @param string $fieldIdentifier
     */
    public function __construct($fieldIdentifier)
    {
        parent::__construct("Field with identifier '$fieldIdentifier' has empty value.");
    }
}
