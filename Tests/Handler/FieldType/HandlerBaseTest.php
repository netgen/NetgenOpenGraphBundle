<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler\FieldType;

use eZ\Publish\API\Repository\Values\Content\Field;

abstract class HandlerBaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $fieldHelper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $translationHelper;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $content;

    /**
     * @var Field
     */
    protected $field;
}
