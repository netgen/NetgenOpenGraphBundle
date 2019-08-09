<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Tests\Stubs;

use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use stdClass;

final class InvalidHandler implements HandlerInterface
{
    public function getMetaTags(string $tagName, array $params = []): array
    {
        return [
            new stdClass(),
        ];
    }
}
