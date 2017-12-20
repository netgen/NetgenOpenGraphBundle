<?php

namespace Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NetgenOpenGraphExtension extends AbstractExtension
{
    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction(
                'render_netgen_open_graph',
                array(NetgenOpenGraphRuntime::class, 'renderOpenGraphTags'),
                array('is_safe' => array('html'))
            ),
            new TwigFunction(
                'get_netgen_open_graph',
                array(NetgenOpenGraphRuntime::class, 'getOpenGraphTags')
            ),
        );
    }
}
