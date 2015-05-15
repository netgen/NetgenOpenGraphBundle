<?php

namespace Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension;

use eZ\Publish\API\Repository\Values\Content\Content;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Psr\Log\LoggerInterface;
use Twig_SimpleFunction;
use Twig_Extension;
use Exception;

class NetgenOpenGraphExtension extends Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct( ContainerInterface $container, LoggerInterface $logger = null )
    {
        $this->container = $container;
        $this->logger = $logger;
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
        $tagCollector = $this->container->get( 'netgen_open_graph.meta_tag_collector' );
        $tagRenderer = $this->container->get( 'netgen_open_graph.meta_tag_renderer' );

        try
        {
            return $tagRenderer->render(
                $tagCollector->collect( $content )
            );
        }
        catch ( Exception $e )
        {
            if ( !$this->logger instanceof LoggerInterface || $this->container->getParameter( 'kernel.debug' ) )
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
        $tagCollector = $this->container->get( 'netgen_open_graph.meta_tag_collector' );

        try
        {
            return $tagCollector->collect( $content );
        }
        catch ( Exception $e )
        {
            if ( !$this->logger instanceof LoggerInterface || $this->container->getParameter( 'kernel.debug' ) )
            {
                throw $e;
            }

            $this->logger->error( $e->getMessage() );
        }

        return array();
    }
}
