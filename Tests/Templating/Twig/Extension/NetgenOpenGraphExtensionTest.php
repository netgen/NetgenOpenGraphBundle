<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\Templating\Twig\Extension;

use Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension\NetgenOpenGraphExtension;
use PHPUnit\Framework\TestCase;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;

class NetgenOpenGraphExtensionTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension\NetgenOpenGraphExtension
     */
    protected $extension;

    public function setUp()
    {
        $this->extension = new NetgenOpenGraphExtension();
    }

    public function testInstanceOfTwigExtensionInterface()
    {
        $this->assertInstanceOf(ExtensionInterface::class, $this->extension);
    }

    public function testGetFunctions()
    {
        foreach ($this->extension->getFunctions() as $function) {
            $this->assertInstanceOf(TwigFunction::class, $function);
        }
    }
}
