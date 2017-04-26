<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Netgen\Bundle\OpenGraphBundle\DependencyInjection\NetgenOpenGraphExtension;

class NetgenOpenGraphExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions()
    {
        return [
            new NetgenOpenGraphExtension(),
        ];
    }

    public function testItSetsValidContainerParameters()
    {
        $this->container->setParameter('ezpublish.siteaccess.list', []);
        $this->load();
    }

    protected function getMinimalConfiguration()
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
                                ]
                            ],
                            [
                                'handler' => 'field_type/ezstring',
                                'tag' => 'og:title',
                                'params' => [
                                    'one',
                                    'two',
                                    'three',
                                ]
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
                                ]
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
                            ]
                        ],
                        [
                            'handler' => 'field_type/ezstring',
                            'tag' => 'og:title',
                            'params' => [
                                'one',
                                'two',
                                'three',
                            ]
                        ],
                    ],
                ],
            ],
        ];
    }
}
