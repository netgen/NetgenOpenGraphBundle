<?php

namespace Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension;

use Twig\RuntimeLoader\RuntimeLoaderInterface;

class NetgenOpenGraphRuntimeLoader implements RuntimeLoaderInterface
{
    /**
     * @var \Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension\NetgenOpenGraphRuntime
     */
    protected $runtime;

    public function __construct(NetgenOpenGraphRuntime $runtime)
    {
        $this->runtime = $runtime;
    }

    public function load($class)
    {
        if (!is_a($this->runtime, $class, true)) {
            return;
        }

        return $this->runtime;
    }
}
