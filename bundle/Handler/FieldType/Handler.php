<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler\FieldType;

use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Ibexa\Core\Base\Exceptions\InvalidArgumentException;
use Ibexa\Core\Helper\FieldHelper;
use Netgen\Bundle\OpenGraphBundle\Exception\FieldEmptyException;
use Netgen\Bundle\OpenGraphBundle\Handler\Handler as BaseHandler;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;

use function is_array;

abstract class Handler extends BaseHandler
{
    protected FieldHelper $fieldHelper;

    public function __construct(FieldHelper $fieldHelper)
    {
        $this->fieldHelper = $fieldHelper;
    }

    public function getMetaTags(string $tagName, array $params = []): array
    {
        if (!isset($params[0])) {
            throw new InvalidArgumentException(
                '$params[0]',
                'Field type handlers require at least a field identifier.',
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
                $fieldValue,
            ),
        ];
    }

    /**
     * Returns the field value, converted to string.
     *
     * @throws \Netgen\Bundle\OpenGraphBundle\Exception\FieldEmptyException If field is empty
     */
    protected function getFieldValue(Field $field, string $tagName, array $params = []): string
    {
        if (!$this->fieldHelper->isFieldEmpty($this->content, $field->fieldDefIdentifier)) {
            return (string) $field->value;
        }

        throw new FieldEmptyException($field->fieldDefIdentifier);
    }

    /**
     * Returns fallback value.
     */
    protected function getFallbackValue(string $tagName, array $params = []): string
    {
        if (!empty($params[1])) {
            return (string) $params[1];
        }

        return '';
    }

    /**
     * Validates field by field identifier.
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException If field does not exist, or the handler does not support it
     */
    protected function validateField(string $fieldIdentifier): Field
    {
        $field = $this->content->getField($fieldIdentifier);
        if (!$field instanceof Field) {
            throw new InvalidArgumentException('$params[0]', 'Field \'' . $fieldIdentifier . '\' does not exist in content.');
        }

        if (!$this->supports($field)) {
            throw new InvalidArgumentException(
                '$params[0]',
                static::class . ' field type handler does not support field with identifier \'' . $field->fieldDefIdentifier . '\'.',
            );
        }

        return $field;
    }

    /**
     * Returns if this field type handler supports current field.
     */
    abstract protected function supports(Field $field): bool;
}
