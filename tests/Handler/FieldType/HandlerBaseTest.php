<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Handler\FieldType;

use PHPUnit\Framework\TestCase;

abstract class HandlerBaseTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $fieldHelper;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $translationHelper;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $content;

    /**
     * @var \eZ\Publish\API\Repository\Values\Content\Field
     */
    private $field;
}
