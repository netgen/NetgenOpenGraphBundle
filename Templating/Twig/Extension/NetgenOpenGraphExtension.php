<?php

namespace Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension;

use eZ\Publish\API\Repository\Values\Content\Content;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig_Extension;
use Twig_SimpleFunction;

class NetgenOpenGraphExtension extends Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct( ContainerInterface $container )
    {
        $this->container = $container;
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

        return $tagRenderer->render(
            $tagCollector->collect( $content )
        );
    }

    /**
     * Returns Open Graph tags for provided content
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return string
     */
    public function getOpenGraphTags( Content $content )
    {
        $tagCollector = $this->container->get( 'netgen_open_graph.meta_tag_collector' );

        return $tagCollector->collect( $content );
    }
}
