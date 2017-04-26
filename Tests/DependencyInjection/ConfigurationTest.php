<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use Netgen\Bundle\OpenGraphBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    public function testConfigurationValuesAreOkAndValid()
    {
        $this->assertConfigurationIsValid(
            array(
                'netgen_open_graph' => array(
                    'system' => array(
                        'default' => array(
                            'content_type_handlers' => array(
                                'content_type_one' => array(
                                    array(
                                        'handler' => 'literal/text',
                                        'tag' => 'og:type',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                ),
                                'content_type_two' => array(
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                ),
                            ),
                            'global_handlers' => array(
                                array(
                                    'handler' => 'literal/text',
                                    'tag' => 'og:type',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                                array(
                                    'handler' => 'field_type/ezstring',
                                    'tag' => 'og:title',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            )
        );
    }

    public function testConfigurationWithoutRequiredHandlerInContentTypeHandlers()
    {
        $this->assertConfigurationIsInvalid(
            array(
                'netgen_open_graph' => array(
                    'system' => array(
                        'default' => array(
                            'content_type_handlers' => array(
                                'content_type_one' => array(
                                    array(
                                        'tag' => 'og:type',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                ),
                                'content_type_two' => array(
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                ),
                            ),
                            'global_handlers' => array(
                                array(
                                    'handler' => 'literal/text',
                                    'tag' => 'og:type',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                                array(
                                    'handler' => 'field_type/ezstring',
                                    'tag' => 'og:title',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'netgen_open_graph.system.default.content_type_handlers.content_type_one'
        );
    }

    public function testConfigurationWithHandlerEmptyInContentTypeHandlers()
    {
        $this->assertConfigurationIsInvalid(
            array(
                'netgen_open_graph' => array(
                    'system' => array(
                        'default' => array(
                            'content_type_handlers' => array(
                                'content_type_one' => array(
                                    array(
                                        'handler' => '',
                                        'tag' => 'og:type',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                ),
                                'content_type_two' => array(
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                ),
                            ),
                            'global_handlers' => array(
                                array(
                                    'handler' => 'literal/text',
                                    'tag' => 'og:type',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                                array(
                                    'handler' => 'field_type/ezstring',
                                    'tag' => 'og:title',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'netgen_open_graph.system.default.content_type_handlers.content_type_one'
        );
    }

    public function testConfigurationWithoutRequiredTagInContentTypeHandlers()
    {
        $this->assertConfigurationIsInvalid(
            array(
                'netgen_open_graph' => array(
                    'system' => array(
                        'default' => array(
                            'content_type_handlers' => array(
                                'content_type_one' => array(
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                    ),
                                ),
                                'content_type_two' => array(
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                ),
                            ),
                            'global_handlers' => array(
                                array(
                                    'handler' => 'literal/text',
                                    'tag' => 'og:type',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                                array(
                                    'handler' => 'field_type/ezstring',
                                    'tag' => 'og:title',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'netgen_open_graph.system.default.content_type_handlers.content_type_one'
        );
    }

    public function testConfigurationWithTagEmptyInContentTypeHandlers()
    {
        $this->assertConfigurationIsInvalid(
            array(
                'netgen_open_graph' => array(
                    'system' => array(
                        'default' => array(
                            'content_type_handlers' => array(
                                'content_type_one' => array(
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => '',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                    ),
                                ),
                                'content_type_two' => array(
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                ),
                            ),
                            'global_handlers' => array(
                                array(
                                    'handler' => 'literal/text',
                                    'tag' => 'og:type',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                                array(
                                    'handler' => 'field_type/ezstring',
                                    'tag' => 'og:title',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'netgen_open_graph.system.default.content_type_handlers.content_type_one'
        );
    }

    public function testConfigurationWithoutRequiredHandlerInGlobalHandlers()
    {
        $this->assertConfigurationIsInvalid(
            array(
                'netgen_open_graph' => array(
                    'system' => array(
                        'default' => array(
                            'content_type_handlers' => array(
                                'content_type_one' => array(
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:type',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                ),
                                'content_type_two' => array(
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                ),
                            ),
                            'global_handlers' => array(
                                array(
                                    'tag' => 'og:type',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                                array(
                                    'handler' => 'field_type/ezstring',
                                    'tag' => 'og:title',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'netgen_open_graph.system.default.global_handlers'
        );
    }

    public function testConfigurationWithHandlerEmptyInGlobalHandlers()
    {
        $this->assertConfigurationIsInvalid(
            array(
                'netgen_open_graph' => array(
                    'system' => array(
                        'default' => array(
                            'content_type_handlers' => array(
                                'content_type_one' => array(
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:type',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                ),
                                'content_type_two' => array(
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                ),
                            ),
                            'global_handlers' => array(
                                array(
                                    'handler' => '',
                                    'tag' => 'og:type',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                                array(
                                    'handler' => 'field_type/ezstring',
                                    'tag' => 'og:title',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'netgen_open_graph.system.default.global_handlers'
        );
    }

    public function testConfigurationWithoutRequiredTagInGlobalHandlers()
    {
        $this->assertConfigurationIsInvalid(
            array(
                'netgen_open_graph' => array(
                    'system' => array(
                        'default' => array(
                            'content_type_handlers' => array(
                                'content_type_one' => array(
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:type',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                ),
                                'content_type_two' => array(
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                ),
                            ),
                            'global_handlers' => array(
                                array(
                                    'handler' => 'field_type/ezstring',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                                array(
                                    'handler' => 'field_type/ezstring',
                                    'tag' => 'og:title',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'netgen_open_graph.system.default.global_handlers'
        );
    }

    public function testConfigurationWithTagEmptyInGlobalHandlers()
    {
        $this->assertConfigurationIsInvalid(
            array(
                'netgen_open_graph' => array(
                    'system' => array(
                        'default' => array(
                            'content_type_handlers' => array(
                                'content_type_one' => array(
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:type',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                ),
                                'content_type_two' => array(
                                    array(
                                        'handler' => 'field_type/ezstring',
                                        'tag' => 'og:title',
                                        'params' => array(
                                            'one',
                                            'two',
                                            'three',
                                        ),
                                    ),
                                ),
                            ),
                            'global_handlers' => array(
                                array(
                                    'handler' => 'field_type/ezstring',
                                    'tag' => '',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                                array(
                                    'handler' => 'field_type/ezstring',
                                    'tag' => 'og:title',
                                    'params' => array(
                                        'one',
                                        'two',
                                        'three',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'netgen_open_graph.system.default.global_handlers'
        );
    }

    protected function getConfiguration()
    {
        return new Configuration();
    }
}
