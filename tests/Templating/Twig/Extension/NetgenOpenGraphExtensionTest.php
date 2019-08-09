<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Templating\Twig\Extension;

use Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension\NetgenOpenGraphExtension;
use PHPUnit\Framework\TestCase;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;

final class NetgenOpenGraphExtensionTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension\NetgenOpenGraphExtension
     */
    private $extension;

    protected function setUp(): void
    {
        $this->extension = new NetgenOpenGraphExtension();
    }

    public function testInstanceOfTwigExtensionInterface(): void
    {
        self::assertInstanceOf(ExtensionInterface::class, $this->extension);
    }

    public function testGetFunctions(): void
    {
        foreach ($this->extension->getFunctions() as $function) {
            self::assertInstanceOf(TwigFunction::class, $function);
        }
    }
}
