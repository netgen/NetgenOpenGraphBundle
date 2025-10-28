<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler\Literal;

use Ibexa\Core\Base\Exceptions\InvalidArgumentException;
use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

use function ltrim;
use function preg_match;

final class Url implements HandlerInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
    ) {}

    public function getMetaTags(string $tagName, array $params = []): array
    {
        if (!isset($params[0])) {
            throw new InvalidArgumentException(
                '$params[0]',
                'Literal URL handler requires the path to output.',
            );
        }

        $path = $params[0];
        $request = $this->requestStack->getCurrentRequest();

        if ($request instanceof Request && !preg_match('/^https?:\/\//', $path)) {
            $path = $request->getUriForPath('/' . ltrim($path, '/'));
        }

        return [
            new Item(
                $tagName,
                $path,
            ),
        ];
    }
}
