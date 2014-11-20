<?php

namespace Netgen\Bundle\OpenGraphBundle\MetaTag;

use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use Netgen\Bundle\OpenGraphBundle\Handler\Registry;
use Netgen\Bundle\OpenGraphBundle\Handler\ContentAware;
use LogicException;

class Collector
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
     * Constructor
     *
     * @param \Netgen\Bundle\OpenGraphBundle\Handler\Registry $metaTagHandlers
     * @param \eZ\Publish\API\Repository\ContentTypeService $contentTypeService
     * @param \eZ\Publish\Core\MVC\ConfigResolverInterface $configResolver
     */
    public function __construct( Registry $metaTagHandlers, ContentTypeService $contentTypeService, ConfigResolverInterface $configResolver )
    {
        $this->metaTagHandlers = $metaTagHandlers;
        $this->contentTypeService = $contentTypeService;
        $this->configResolver = $configResolver;
    }

    /**
     * Collects meta tags from all handlers registered for provided content
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return \Netgen\Bundle\OpenGraphBundle\MetaTag\Item[]
     */
    public function collect( Content $content )
    {
        $metaTags = array();

        $contentType = $this->contentTypeService->loadContentType( $content->contentInfo->contentTypeId );
        $contentTypeHandlers = $this->configResolver->getParameter( 'content_type_handlers', 'netgen_open_graph' );

        if ( !isset( $contentTypeHandlers[$contentType->identifier] ) )
        {
            return array();
        }

        foreach ( $contentTypeHandlers[$contentType->identifier] as $contentTypeHandler )
        {
            $metaTagHandler = $this->metaTagHandlers->getHandler( $contentTypeHandler['handler'] );
            if ( $metaTagHandler instanceof ContentAware )
            {
                $metaTagHandler->setContent( $content );
            }

            $newMetaTags = $metaTagHandler->getMetaTags( $contentTypeHandler['params'] );
            foreach ( $newMetaTags as $metaTag )
            {
                if ( !$metaTag instanceof Item )
                {
                    throw new LogicException(
                        '\'' . $contentTypeHandler['handler'] . '\' handler returned wrong value.' .
                        ' Expected \'Netgen\Bundle\OpenGraphBundle\MetaTag\Item\', got \'' . get_class( $metaTag ) . '\'.' );
                }

                $metaTagValue = $metaTag->getTagValue();
                if ( !empty( $metaTagValue ) )
                {
                    $metaTags[] = $metaTag;
                }
            }
        }

        return $metaTags;
    }
}
