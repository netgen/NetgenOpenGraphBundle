<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class NetgenOpenGraphExtension extends AbstractExtension
{
    public function getFunctions(): array
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
