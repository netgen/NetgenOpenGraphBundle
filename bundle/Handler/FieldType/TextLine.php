<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler\FieldType;

use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\TextLine\Value;

class TextLine extends Handler
{
    protected function supports(Field $field): bool
    {
        return $field->value instanceof Value;
    }
}
