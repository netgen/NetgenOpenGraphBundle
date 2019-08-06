<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler\FieldType;

use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\RichText\Value;
use Netgen\Bundle\OpenGraphBundle\Exception\FieldEmptyException;

class RichText extends XmlText
{
    protected function supports(Field $field): bool
    {
        return $field->value instanceof Value;
    }
}
