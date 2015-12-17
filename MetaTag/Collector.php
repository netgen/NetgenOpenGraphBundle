<?php

namespace Netgen\Bundle\OpenGraphBundle\MetaTag;

use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use Netgen\Bundle\OpenGraphBundle\Handler\Registry;
use Netgen\Bundle\OpenGraphBundle\Handler\ContentAware;
use LogicException;

class Collector implements CollectorInterface
{
    /**
     * @var \Netgen\Bundle\OpenGraphBundle\Handler\Registry
     */
    protected $metaTagHandlers;

    /**
     * @var \eZ\Publish\API\Repository\ContentTypeService
     */
    protected $contentTypeService;

    /**
     * @var \eZ\Publish\Core\MVC\ConfigResolverInterface
     */
    protected $configResolver;

    /**
     * Constructor.
     *
     * @param \Netgen\Bundle\OpenGraphBundle\Handler\Registry $metaTagHandlers
     * @param \eZ\Publish\API\Repository\ContentTypeService $contentTypeService
     * @param \eZ\Publish\Core\MVC\ConfigResolverInterface $configResolver
     */
    public function __construct(Registry $metaTagHandlers, ContentTypeService $contentTypeService, ConfigResolverInterface $configResolver)
    {
        $this->metaTagHandlers = $metaTagHandlers;
        $this->contentTypeService = $contentTypeService;
        $this->configResolver = $configResolver;
    }

    /**
     * Collects meta tags from all handlers registered for provided content.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return \Netgen\Bundle\OpenGraphBundle\MetaTag\Item[]
     */
    public function collect(Content $content)
    {
        $metaTags = array();

        $allHandlers = $this->configResolver->hasParameter('global_handlers', 'netgen_open_graph') ?
            $this->configResolver->getParameter('global_handlers', 'netgen_open_graph') :
            array();

        $contentType = $this->contentTypeService->loadContentType($content->contentInfo->contentTypeId);
        $contentTypeHandlers = $this->configResolver->hasParameter('content_type_handlers', 'netgen_open_graph') ?
            $this->configResolver->getParameter('content_type_handlers', 'netgen_open_graph') :
            array();

        if (isset($contentTypeHandlers[$contentType->identifier])) {
            $allHandlers = array_merge(
                isset($allHandlers['all_content_types']) ? $allHandlers['all_content_types'] : array(),
                $contentTypeHandlers[$contentType->identifier]
            );
        } else {
            $allHandlers = isset($allHandlers['all_content_types']) ? $allHandlers['all_content_types'] : array();
        }

        foreach ($allHandlers as $handler) {
            $metaTagHandler = $this->metaTagHandlers->getHandler($handler['handler']);
            if ($metaTagHandler instanceof ContentAware) {
                $metaTagHandler->setContent($content);
            }

            $newMetaTags = $metaTagHandler->getMetaTags($handler['tag'], $handler['params']);
            foreach ($newMetaTags as $metaTag) {
                if (!$metaTag instanceof Item) {
                    throw new LogicException(
                        '\'' . $handler['handler'] . '\' handler returned wrong value.' .
                        ' Expected \'Netgen\Bundle\OpenGraphBundle\MetaTag\Item\', got \'' . get_class($metaTag) . '\'.');
                }

                $metaTagValue = $metaTag->getTagValue();
                if (!empty($metaTagValue)) {
                    $metaTags[] = $metaTag;
                }
            }
        }

        return $metaTags;
    }
}
