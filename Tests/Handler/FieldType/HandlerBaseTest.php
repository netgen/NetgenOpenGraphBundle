<?php

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler\FieldType;

use eZ\Publish\Core\Helper\FieldHelper;
use eZ\Publish\Core\Helper\TranslationHelper;

abstract class HandlerBaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FieldHelper
     */
    protected $fieldHelper;

    /**
     * @var TranslationHelper
     */
    protected $translationHelper;
}
