<?php

namespace Netgen\Bundle\OpenGraphBundle\Handler\FieldType;

use eZ\Publish\Core\Helper\FieldHelper;
use eZ\Publish\Core\Helper\TranslationHelper;
use eZ\Publish\API\Repository\Values\Content\Field;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use Netgen\Bundle\OpenGraphBundle\Handler\Handler as BaseHandler;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;

abstract class Handler extends BaseHandler
{
    /**
     * @var \eZ\Publish\Core\Helper\FieldHelper
     */
    protected $fieldHelper;

    /**
     * @var \eZ\Publish\Core\Helper\TranslationHelper
     */
    protected $translationHelper;

    /**
     * Constructor
     *
     * @param \eZ\Publish\Core\Helper\FieldHelper $fieldHelper
     * @param \eZ\Publish\Core\Helper\TranslationHelper $translationHelper
     */
    public function __construct( FieldHelper $fieldHelper, TranslationHelper $translationHelper )
    {
        $this->fieldHelper = $fieldHelper;
        $this->translationHelper = $translationHelper;
    }

    /**
     * Returns the array of meta tags
     *
     * @param array $params
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException If number of params is incorrect
     *
     * @return \Netgen\Bundle\OpenGraphBundle\MetaTag\Item[]
     */
    public function getMetaTags( array $params = array() )
    {
        if ( count( $params ) < 2 )
        {
            throw new InvalidArgumentException(
                '$params',
                'Field type handlers require at least two parameters, meta tag name and field name.'
            );
        }

        $field = $this->translationHelper->getTranslatedField( $this->content, $params[1] );
        if ( !$field instanceof Field )
        {
            throw new InvalidArgumentException( '$params[1]', 'Field \'' . $params[1] . '\' does not exist in content.' );
        }

        if ( !$this->supports( $field ) )
        {
            return array();
        }

        $metaTagValue = '';
        if ( !$this->fieldHelper->isFieldEmpty( $this->content, $params[1] ) )
        {
            $metaTagValue = $this->getFieldValue( $field );
        }
        else if ( !empty( $params[2] ) )
        {
            $metaTagValue = (string)$params[2];
        }

        return array(
            new Item(
                $params[0],
                $metaTagValue
            )
        );
    }

    /**
     * Returns the field value, converted to string
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Field $field
     *
     * @return string
     */
    protected function getFieldValue( Field $field )
    {
        return (string)$field->value;
    }

    /**
     * Returns if this field type handler supports current field
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Field $field
     *
     * @return bool
     */
    abstract protected function supports( Field $field );
}
