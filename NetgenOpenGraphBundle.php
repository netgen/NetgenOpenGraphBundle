<?php

namespace Netgen\Bundle\OpenGraphBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Netgen\Bundle\OpenGraphBundle\DependencyInjection\Compiler\MetaTagHandlersCompilerPass;

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
    }
}
