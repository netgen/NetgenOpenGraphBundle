<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Netgen\Bundle\OpenGraphBundle\DependencyInjection\NetgenOpenGraphExtension;

class NetgenOpenGraphExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testItSetsValidContainerParameters()
    {
        $this->container->setParameter('ezpublish.siteaccess.list', array());
        $this->load();
    }

    protected function getContainerExtensions(): array
    {
        return array(
            new NetgenOpenGraphExtension(),
        );
    }

    protected function getMinimalConfiguration(): array
    {
        return array(
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
        );
    }
}
