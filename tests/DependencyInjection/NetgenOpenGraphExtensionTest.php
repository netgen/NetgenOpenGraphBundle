<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Netgen\Bundle\OpenGraphBundle\DependencyInjection\NetgenOpenGraphExtension;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;

final class NetgenOpenGraphExtensionTest extends AbstractExtensionTestCase
{
    #[DoesNotPerformAssertions]
    public function testItSetsValidContainerParameters(): void
    {
        $this->container->setParameter('ibexa.site_access.list', []);
        $this->load();
    }

    protected function getContainerExtensions(): array
    {
        return [
            new NetgenOpenGraphExtension(),
        ];
    }

    protected function getMinimalConfiguration(): array
    {
        return [
            'system' => [
                'default' => [
                    'content_type_handlers' => [
                        'content_type_one' => [
                            [
                                'handler' => 'literal/text',
                                'tag' => 'og:type',
                                'params' => [
                                    'one',
                                    'two',
                                    'three',
                                ],
                            ],
                            [
                                'handler' => 'field_type/ibexa_string',
                                'tag' => 'og:title',
                                'params' => [
                                    'one',
                                    'two',
                                    'three',
                                ],
                            ],
                        ],
                        'content_type_two' => [
                            [
                                'handler' => 'field_type/ibexa_string',
                                'tag' => 'og:title',
                                'params' => [
                                    'one',
                                    'two',
                                    'three',
                                ],
                            ],
                        ],
                    ],
                    'global_handlers' => [
                        [
                            'handler' => 'literal/text',
                            'tag' => 'og:type',
                            'params' => [
                                'one',
                                'two',
                                'three',
                            ],
                        ],
                        [
                            'handler' => 'field_type/ibexa_string',
                            'tag' => 'og:title',
                            'params' => [
                                'one',
                                'two',
                                'three',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
