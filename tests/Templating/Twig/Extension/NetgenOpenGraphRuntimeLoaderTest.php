<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Templating\Twig\Extension;

use Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension\NetgenOpenGraphRuntime;
use Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension\NetgenOpenGraphRuntimeLoader;
use PHPUnit\Framework\TestCase;

class NetgenOpenGraphRuntimeLoaderTest extends TestCase
{
    /**
     * @var NetgenOpenGraphRuntimeLoader
     */
    protected $loader;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $runtime;

    protected function setUp(): void
    {
        $this->runtime = $this->createMock(NetgenOpenGraphRuntime::class);
        $this->loader = new NetgenOpenGraphRuntimeLoader($this->runtime);
    }

    public function testLoad()
    {
        $runtime = $this->loader->load(NetgenOpenGraphRuntime::class);
        self::assertSame($this->runtime, $runtime);
    }

    public function testLoadWithStdClass()
    {
        self::assertNull($this->loader->load(\stdClass::class));
    }
}
