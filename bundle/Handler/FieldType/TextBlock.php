<?php

namespace Netgen\Bundle\OpenGraphBundle\Handler\FieldType;

use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\TextBlock\Value;

class TextBlock extends Handler
{
    /**
     * Returns if this field type handler supports current field.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Field $field
     *
     * @return bool
     */
    protected function supports(Field $field)
    {
        return $field->value instanceof Value;
    }
}
