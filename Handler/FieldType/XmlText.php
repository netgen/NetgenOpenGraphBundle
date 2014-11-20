<?php

namespace Netgen\Bundle\OpenGraphBundle\Handler\FieldType;

use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\XmlText\Value;

class XmlText extends Handler
{
    /**
     * Returns the field value, converted to string
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Field $field
     *
     * @return string
     */
    protected function getFieldValue( Field $field )
    {
        /** @var \eZ\Publish\Core\FieldType\XmlText\Value $value */
        $value = $field->value;
        return trim( str_replace( "\n", " ", strip_tags( $value->xml->saveXML() ) ) );
    }

    /**
     * Returns if this field type handler supports current field
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Field $field
     *
     * @return bool
     */
    protected function supports( Field $field )
    {
        return $field->value instanceof Value;
    }
}
