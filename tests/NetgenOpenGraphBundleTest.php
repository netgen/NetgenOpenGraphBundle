<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests;

use Netgen\Bundle\OpenGraphBundle\DependencyInjection\Compiler\MetaTagHandlersCompilerPass;
use Netgen\Bundle\OpenGraphBundle\DependencyInjection\Compiler\TwigRuntimePass;
use Netgen\Bundle\OpenGraphBundle\NetgenOpenGraphBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NetgenOpenGraphBundleTest extends TestCase
{
    public function testItAddsCompilerPass()
    {
        $container = $this->getMockBuilder(ContainerBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['addCompilerPass'])
            ->getMock();

        $container->expects(self::at(0))
            ->method('addCompilerPass')
            ->with(new MetaTagHandlersCompilerPass());

        $container->expects(self::at(1))
            ->method('addCompilerPass')
            ->with(new TwigRuntimePass());

        $bundle = new NetgenOpenGraphBundle();
        $bundle->build($container);
    }
}
