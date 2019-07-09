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
use Psr\Log\NullLogger;
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

    public function __construct(
        FieldHelper $fieldHelper,
        TranslationHelper $translationHelper,
        VariationHandler $imageVariationService,
        RequestStack $requestStack,
        ?LoggerInterface $logger = null
    ) {
        parent::__construct($fieldHelper, $translationHelper);

        $this->imageVariationService = $imageVariationService;
        $this->requestStack = $requestStack;
        $this->logger = $logger ?? new NullLogger();
    }

    protected function getFieldValue(Field $field, string $tagName, array $params = []): string
    {
        if (!$this->fieldHelper->isFieldEmpty($this->content, $field->fieldDefIdentifier)) {
            $variationName = !empty($params[1]) ? $params[1] : 'opengraph';

            try {
                $variationUri = $this->imageVariationService->getVariation($field, $this->content->versionInfo, $variationName)->uri;

                if (mb_strpos($variationUri, '/') === 0 && ($request = $this->requestStack->getCurrentRequest()) !== null) {
                    $variationUri = $request->getUriForPath('/' . ltrim($variationUri, '/'));
                }

                return $variationUri;
            } catch (InvalidVariationException $e) {
                $this->logger->error("Open Graph image handler: Couldn't get variation '{$variationName}' for image with id {$field->value->id}");
            } catch (SourceImageNotFoundException $e) {
                $this->logger->error(
                    "Open Graph image handler: Couldn't create variation '{$variationName}' for image with id {$field->value->id} because source image can't be found"
                );
            } catch (Exception $e) {
                $this->logger->error(
                    "Open Graph image handler: Error while getting variation '{$variationName}' for image with id {$field->value->id}: " . $e->getMessage()
                );
            }
        }

        throw new FieldEmptyException($field->fieldDefIdentifier);
    }

    protected function getFallbackValue(string $tagName, array $params = []): string
    {
        if (!empty($params[2]) && ($request = $this->requestStack->getCurrentRequest()) !== null) {
            return $request->getUriForPath('/' . ltrim($params[2], '/'));
        }

        return '';
    }

    protected function supports(Field $field): bool
    {
        return $field->value instanceof Value;
    }
}
