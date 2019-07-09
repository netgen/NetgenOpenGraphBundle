<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler\FieldType;

use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;
use eZ\Publish\Core\Helper\FieldHelper;
use eZ\Publish\Core\Helper\TranslationHelper;
use Netgen\Bundle\OpenGraphBundle\Exception\FieldEmptyException;
use Netgen\Bundle\OpenGraphBundle\Handler\Handler as BaseHandler;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;

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
     * Constructor.
     *
     * @param \eZ\Publish\Core\Helper\FieldHelper $fieldHelper
     * @param \eZ\Publish\Core\Helper\TranslationHelper $translationHelper
     */
    public function __construct(FieldHelper $fieldHelper, TranslationHelper $translationHelper)
    {
        $this->fieldHelper = $fieldHelper;
        $this->translationHelper = $translationHelper;
    }

    /**
     * Returns the array of meta tags.
     *
     * @param string $tagName
     * @param array $params
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException If number of params is incorrect
     *
     * @return \Netgen\Bundle\OpenGraphBundle\MetaTag\Item[]
     */
    public function getMetaTags($tagName, array $params = [])
    {
        if (!isset($params[0])) {
            throw new InvalidArgumentException(
                '$params[0]',
                'Field type handlers require at least a field identifier.'
            );
        }

        $fieldIdentifiers = is_array($params[0]) ? $params[0] : [$params[0]];
        $fieldValue = $this->getFallbackValue($tagName, $params);

        foreach ($fieldIdentifiers as $fieldIdentifier) {
            $field = $this->validateField($fieldIdentifier);

            try {
                $fieldValue = $this->getFieldValue($field, $tagName, $params);

                break;
            } catch (FieldEmptyException $e) {
                // do nothing
            }
        }

        return [
            new Item(
                $tagName,
                $fieldValue
            ),
        ];
    }

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
    protected function getFieldValue(Field $field, $tagName, array $params = [])
    {
        if (!$this->fieldHelper->isFieldEmpty($this->content, $field->fieldDefIdentifier)) {
            return (string) $field->value;
        }

        throw new FieldEmptyException($field->fieldDefIdentifier);
    }

    /**
     * Returns fallback value.
     *
     * @param string $tagName
     * @param array $params
     *
     * @return string
     */
    protected function getFallbackValue($tagName, array $params = [])
    {
        if (!empty($params[1])) {
            return (string) $params[1];
        }

        return '';
    }

    /**
     * Validates field by field identifier.
     *
     * @param string $fieldIdentifier
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException If field does not exist, or the handler does not support it
     *
     * @returns \eZ\Publish\API\Repository\Values\Content\Field
     */
    protected function validateField($fieldIdentifier)
    {
        $field = $this->translationHelper->getTranslatedField($this->content, $fieldIdentifier);
        if (!$field instanceof Field) {
            throw new InvalidArgumentException('$params[0]', 'Field \'' . $fieldIdentifier . '\' does not exist in content.');
        }

        if (!$this->supports($field)) {
            throw new InvalidArgumentException(
                '$params[0]',
                get_class($this) . ' field type handler does not support field with identifier \'' . $field->fieldDefIdentifier . '\'.'
            );
        }

        return $field;
    }

    /**
     * Returns if this field type handler supports current field.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Field $field
     *
     * @return bool
     */
    abstract protected function supports(Field $field);
}
