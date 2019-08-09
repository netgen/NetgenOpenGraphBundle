<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Netgen\Bundle\OpenGraphBundle\DependencyInjection\NetgenOpenGraphExtension;

final class NetgenOpenGraphExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testItSetsValidContainerParameters(): void
    {
        $this->container->setParameter('ezpublish.siteaccess.list', []);
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
                                'handler' => 'field_type/ezstring',
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
                                'handler' => 'field_type/ezstring',
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
                            'handler' => 'field_type/ezstring',
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
