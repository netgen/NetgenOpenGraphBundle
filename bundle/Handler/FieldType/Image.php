<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler\FieldType;

use Exception;
use eZ\Publish\API\Repository\Exceptions\InvalidVariationException;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\Image\Value;
use eZ\Publish\Core\Helper\FieldHelper;
use eZ\Publish\Core\Helper\TranslationHelper;
use eZ\Publish\Core\MVC\Exception\SourceImageNotFoundException;
use eZ\Publish\SPI\Variation\VariationHandler;
use Netgen\Bundle\OpenGraphBundle\Exception\FieldEmptyException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Image extends Handler
{
    /**
     * @var \eZ\Publish\SPI\Variation\VariationHandler
     */
    protected $imageVariationService;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected $requestStack;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Constructor.
     *
     * @param \eZ\Publish\Core\Helper\FieldHelper $fieldHelper
     * @param \eZ\Publish\Core\Helper\TranslationHelper $translationHelper
     * @param \eZ\Publish\SPI\Variation\VariationHandler $imageVariationService
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        FieldHelper $fieldHelper,
        TranslationHelper $translationHelper,
        VariationHandler $imageVariationService,
        RequestStack $requestStack,
        LoggerInterface $logger = null
    ) {
        parent::__construct($fieldHelper, $translationHelper);

        $this->imageVariationService = $imageVariationService;
        $this->requestStack = $requestStack;
        $this->logger = $logger;
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
            $variationName = !empty($params[1]) ? $params[1] : 'opengraph';

            try {
                $variationUri = $this->imageVariationService->getVariation($field, $this->content->versionInfo, $variationName)->uri;

                if ($variationUri[0] === '/' && ($request = $this->requestStack->getCurrentRequest()) !== null) {
                    $variationUri = $request->getUriForPath('/' . ltrim($variationUri, '/'));
                }

                return $variationUri;
            } catch (InvalidVariationException $e) {
                if ($this->logger !== null) {
                    $this->logger->error("Open Graph image handler: Couldn't get variation '{$variationName}' for image with id {$field->value->id}");
                }
            } catch (SourceImageNotFoundException $e) {
                if ($this->logger !== null) {
                    $this->logger->error(
                        "Open Graph image handler: Couldn't create variation '{$variationName}' for image with id {$field->value->id} because source image can't be found"
                    );
                }
            } catch (Exception $e) {
                if ($this->logger !== null) {
                    $this->logger->error(
                        "Open Graph image handler: Error while getting variation '{$variationName}' for image with id {$field->value->id}: " . $e->getMessage()
                    );
                }
            }
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
        if (!empty($params[2]) && ($request = $this->requestStack->getCurrentRequest()) !== null) {
            return $request->getUriForPath('/' . ltrim($params[2], '/'));
        }

        return '';
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
