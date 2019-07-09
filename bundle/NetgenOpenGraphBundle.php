<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle;

use Netgen\Bundle\OpenGraphBundle\DependencyInjection\Compiler\MetaTagHandlersCompilerPass;
use Netgen\Bundle\OpenGraphBundle\DependencyInjection\Compiler\TwigRuntimePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NetgenOpenGraphBundle extends Bundle
{
    /**
     * Builds the bundle.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new MetaTagHandlersCompilerPass());
        $container->addCompilerPass(new TwigRuntimePass());
    }
}
