<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler\FieldType;

use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Ibexa\Core\FieldType\TextBlock\Value;

final class TextBlock extends Handler
{
    protected function supports(Field $field): bool
    {
        return $field->value instanceof Value;
    }
}
