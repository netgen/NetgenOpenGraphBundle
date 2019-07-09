<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler\FieldType;

use eZ\Publish\API\Repository\Values\Content\Field;
use PHPUnit\Framework\TestCase;

abstract class HandlerBaseTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $fieldHelper;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $translationHelper;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $content;

    /**
     * @var Field
     */
    protected $field;
}
