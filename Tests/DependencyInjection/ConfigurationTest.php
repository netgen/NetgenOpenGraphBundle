<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Netgen\Bundle\OpenGraphBundle\DependencyInjection\Configuration;

class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    protected function getConfiguration()
    {
        return new Configuration();
    }

    public function testConfigurationValuesAreOkAndValid()
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
                ],
            ]
        );
    }

    public function testConfigurationWithoutRequiredHandlerInContentTypeHandlers()
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
                ],
            ],
            "netgen_open_graph.system.default.content_type_handlers.content_type_one"
        );
    }

    public function testConfigurationWithHandlerEmptyInContentTypeHandlers()
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
                ],
            ],
            "netgen_open_graph.system.default.content_type_handlers.content_type_one"
        );
    }

    public function testConfigurationWithoutRequiredTagInContentTypeHandlers()
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
                                        ]
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
                ],
            ],
            "netgen_open_graph.system.default.content_type_handlers.content_type_one"
        );
    }

    public function testConfigurationWithTagEmptyInContentTypeHandlers()
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
                                        ]
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
                ],
            ],
            "netgen_open_graph.system.default.content_type_handlers.content_type_one"
        );
    }

    public function testConfigurationWithoutRequiredHandlerInGlobalHandlers()
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
                ],
            ],
            "netgen_open_graph.system.default.global_handlers"
        );
    }

    public function testConfigurationWithHandlerEmptyInGlobalHandlers()
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
                                    'handler' => '',
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
                ],
            ],
            "netgen_open_graph.system.default.global_handlers"
        );
    }

    public function testConfigurationWithoutRequiredTagInGlobalHandlers()
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
                                    'handler' => 'field_type/ezstring',
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
                ],
            ],
            "netgen_open_graph.system.default.global_handlers"
        );
    }

    public function testConfigurationWithTagEmptyInGlobalHandlers()
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
                                    'handler' => 'field_type/ezstring',
                                    'tag' => '',
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
                ],
            ],
            "netgen_open_graph.system.default.global_handlers"
        );
    }
}
