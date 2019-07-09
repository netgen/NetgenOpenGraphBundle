<?php

declare(strict_types=1);

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

    protected function setUp(): void
    {
        $this->extension = new NetgenOpenGraphExtension();
    }

    public function testInstanceOfTwigExtensionInterface()
    {
        self::assertInstanceOf(ExtensionInterface::class, $this->extension);
    }

    public function testGetFunctions()
    {
        foreach ($this->extension->getFunctions() as $function) {
            self::assertInstanceOf(TwigFunction::class, $function);
        }
    }
}
