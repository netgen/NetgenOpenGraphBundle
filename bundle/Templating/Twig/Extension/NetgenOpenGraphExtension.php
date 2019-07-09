<?php

declare(strict_types=1);

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
        return [
            new TwigFunction(
                'render_netgen_open_graph',
                [NetgenOpenGraphRuntime::class, 'renderOpenGraphTags'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'get_netgen_open_graph',
                [NetgenOpenGraphRuntime::class, 'getOpenGraphTags']
            ),
        ];
    }
}
