<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler\FieldType;

use eZ\Publish\Core\FieldType\XmlText\Value as XmlTextValue;
use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Netgen\Bundle\OpenGraphBundle\Exception\FieldEmptyException;
use function str_replace;
use function strip_tags;
use function trim;

final class XmlText extends Handler
{
    protected function getFieldValue(Field $field, string $tagName, array $params = []): string
    {
        if (!$this->fieldHelper->isFieldEmpty($this->content, $field->fieldDefIdentifier)) {
            return trim(str_replace("\n", ' ', strip_tags($field->value->xml->saveXML())));
        }

        throw new FieldEmptyException($field->fieldDefIdentifier);
    }

    protected function supports(Field $field): bool
    {
        return $field->value instanceof XmlTextValue;
    }
}
