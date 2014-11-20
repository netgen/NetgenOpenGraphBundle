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
     * @param string $tagName
     * @param array $params
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException If number of params is incorrect
     *
     * @return \Netgen\Bundle\OpenGraphBundle\MetaTag\Item[]
     */
    public function getMetaTags( $tagName, array $params = array() )
    {
        if ( !isset( $params[0] ) )
        {
            throw new InvalidArgumentException(
                '$params[0]',
                'Field type handlers require at least a field identifier.'
            );
        }

        $field = $this->translationHelper->getTranslatedField( $this->content, $params[0] );
        if ( !$field instanceof Field )
        {
            throw new InvalidArgumentException( '$params[0]', 'Field \'' . $params[0] . '\' does not exist in content.' );
        }

        if ( !$this->supports( $field ) )
        {
            throw new InvalidArgumentException(
                '$params[0]',
                get_class($this) . ' field type handler does not support field with identifier \'' . $field->fieldDefIdentifier . '\'.'
            );
        }

        $metaTagValue = '';
        if ( !$this->fieldHelper->isFieldEmpty( $this->content, $params[0] ) )
        {
            $metaTagValue = $this->getFieldValue( $field, $tagName, $params );
        }
        else if ( !empty( $params[1] ) )
        {
            $metaTagValue = (string)$params[1];
        }

        return array(
            new Item(
                $tagName,
                $metaTagValue
            )
        );
    }

    /**
     * Returns the field value, converted to string
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Field $field
     * @param string $tagName
     * @param array $params
     *
     * @return string
     */
    protected function getFieldValue( Field $field, $tagName, array $params = array() )
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
