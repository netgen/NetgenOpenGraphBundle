<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use Netgen\Bundle\OpenGraphBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    public function testConfigurationValuesAreOkAndValid(): void
    {
        $this->assertConfigurationIsValid(
            [
                'netgen_open_graph' => [
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
                ],
            ]
        );
    }

    public function testConfigurationWithoutRequiredHandlerInContentTypeHandlers(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                'netgen_open_graph' => [
                    'system' => [
                        'default' => [
                            'content_type_handlers' => [
                                'content_type_one' => [
                                    [
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
                ],
            ],
            'netgen_open_graph.system.default.content_type_handlers.content_type_one'
        );
    }

    public function testConfigurationWithHandlerEmptyInContentTypeHandlers(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                'netgen_open_graph' => [
                    'system' => [
                        'default' => [
                            'content_type_handlers' => [
                                'content_type_one' => [
                                    [
                                        'handler' => '',
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
                ],
            ],
            'netgen_open_graph.system.default.content_type_handlers.content_type_one'
        );
    }

    public function testConfigurationWithoutRequiredTagInContentTypeHandlers(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                'netgen_open_graph' => [
                    'system' => [
                        'default' => [
                            'content_type_handlers' => [
                                'content_type_one' => [
                                    [
                                        'handler' => 'field_type/ezstring',
                                        'params' => [
                                            'one',
                                            'two',
                                            'three',
                                        ],
                                    ],
                                    [
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
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
                ],
            ],
            'netgen_open_graph.system.default.content_type_handlers.content_type_one'
        );
    }

    public function testConfigurationWithTagEmptyInContentTypeHandlers(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                'netgen_open_graph' => [
                    'system' => [
                        'default' => [
                            'content_type_handlers' => [
                                'content_type_one' => [
                                    [
                                        'handler' => 'field_type/ezstring',
                                        'tag' => '',
                                        'params' => [
                                            'one',
                                            'two',
                                            'three',
                                        ],
                                    ],
                                    [
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
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
                ],
            ],
            'netgen_open_graph.system.default.content_type_handlers.content_type_one'
        );
    }

    public function testConfigurationWithoutRequiredHandlerInGlobalHandlers(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                'netgen_open_graph' => [
                    'system' => [
                        'default' => [
                            'content_type_handlers' => [
                                'content_type_one' => [
                                    [
                                        'handler' => 'field_type/ezstring',
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
                ],
            ],
            'netgen_open_graph.system.default.global_handlers'
        );
    }

    public function testConfigurationWithHandlerEmptyInGlobalHandlers(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                'netgen_open_graph' => [
                    'system' => [
                        'default' => [
                            'content_type_handlers' => [
                                'content_type_one' => [
                                    [
                                        'handler' => 'field_type/ezstring',
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
                                    'handler' => '',
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
                ],
            ],
            'netgen_open_graph.system.default.global_handlers'
        );
    }

    public function testConfigurationWithoutRequiredTagInGlobalHandlers(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                'netgen_open_graph' => [
                    'system' => [
                        'default' => [
                            'content_type_handlers' => [
                                'content_type_one' => [
                                    [
                                        'handler' => 'field_type/ezstring',
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
                                    'handler' => 'field_type/ezstring',
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
                ],
            ],
            'netgen_open_graph.system.default.global_handlers'
        );
    }

    public function testConfigurationWithTagEmptyInGlobalHandlers(): void
    {
        $this->assertConfigurationIsInvalid(
            [
                'netgen_open_graph' => [
                    'system' => [
                        'default' => [
                            'content_type_handlers' => [
                                'content_type_one' => [
                                    [
                                        'handler' => 'field_type/ezstring',
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
                                    'handler' => 'field_type/ezstring',
                                    'tag' => '',
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
                ],
            ],
            'netgen_open_graph.system.default.global_handlers'
        );
    }

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }
}
