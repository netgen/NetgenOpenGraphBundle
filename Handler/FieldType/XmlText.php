<?php

namespace Netgen\Bundle\OpenGraphBundle\Handler\FieldType;

use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\XmlText\Value;
use Netgen\Bundle\OpenGraphBundle\Exception\FieldEmptyException;

class XmlText extends Handler
{
    /**
     * Returns the field value, converted to string.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Field $field
     * @param string $tagName
     * @param array $params
     *
     * @throws \Netgen\Bundle\OpenGraphBundle\Exception\FieldEmptyException If field is empty
     *
     * @return string
     */
    protected function getFieldValue(Field $field, $tagName, array $params = array())
    {
        if (!$this->fieldHelper->isFieldEmpty($this->content, $field->fieldDefIdentifier)) {
            return trim(str_replace("\n", ' ', strip_tags($field->value->xml->saveXML())));
        }

        throw new FieldEmptyException($field->fieldDefIdentifier);
    }

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
