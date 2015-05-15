<?php

namespace Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension;

use eZ\Publish\API\Repository\Values\Content\Content;
use Netgen\Bundle\OpenGraphBundle\MetaTag\CollectorInterface;
use Netgen\Bundle\OpenGraphBundle\MetaTag\RendererInterface;
use Psr\Log\LoggerInterface;
use Twig_SimpleFunction;
use Twig_Extension;
use Exception;

class NetgenOpenGraphExtension extends Twig_Extension
{
    /**
     * @var \Netgen\Bundle\OpenGraphBundle\MetaTag\CollectorInterface
     */
    protected $tagCollector;

    /**
     * @var \Netgen\Bundle\OpenGraphBundle\MetaTag\RendererInterface
     */
    protected $tagRenderer;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var bool
     */
    protected $throwExceptions = true;

    /**
     * Constructor
     *
     * @param \Netgen\Bundle\OpenGraphBundle\MetaTag\CollectorInterface $tagCollector
     * @param \Netgen\Bundle\OpenGraphBundle\MetaTag\RendererInterface $tagRenderer
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        CollectorInterface $tagCollector,
        RendererInterface $tagRenderer,
        LoggerInterface $logger = null
    )
    {
        $this->tagCollector = $tagCollector;
        $this->tagRenderer = $tagRenderer;
        $this->logger = $logger;
    }

    /**
     * Sets the flag that determines if the exceptions will thrown instead of logged
     *
     * @param bool $throwExceptions
     */
    public function setThrowExceptions( $throwExceptions = true )
    {
        $this->throwExceptions = $throwExceptions;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'netgen_open_graph';
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction(
                'render_netgen_open_graph',
                array( $this, 'renderOpenGraphTags' ),
                array( 'is_safe' => array( 'html' ) )
            ),
            new Twig_SimpleFunction(
                'get_netgen_open_graph',
                array( $this, 'getOpenGraphTags' )
            )
        );
    }

    /**
     * Renders Open Graph tags for provided content
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return string
     */
    public function renderOpenGraphTags( Content $content )
    {
        try
        {
            return $this->tagRenderer->render(
                $this->tagCollector->collect( $content )
            );
        }
        catch ( Exception $e )
        {
            if ( $this->throwExceptions || !$this->logger instanceof LoggerInterface )
            {
                throw $e;
            }

            $this->logger->error( $e->getMessage() );
        }

        return "";
    }

    /**
     * Returns Open Graph tags for provided content
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return \Netgen\Bundle\OpenGraphBundle\MetaTag\Item[]
     */
    public function getOpenGraphTags( Content $content )
    {
        try
        {
            return $this->tagCollector->collect( $content );
        }
        catch ( Exception $e )
        {
            if ( $this->throwExceptions || !$this->logger instanceof LoggerInterface )
            {
                throw $e;
            }

            $this->logger->error( $e->getMessage() );
        }

        return array();
    }
}
