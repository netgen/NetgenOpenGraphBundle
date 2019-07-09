<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler\Literal;

use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;
use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class Url implements HandlerInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Returns the array of meta tags.
     *
     * @param string $tagName
     * @param array $params
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException If number of params is incorrect
     *
     * @return \Netgen\Bundle\OpenGraphBundle\MetaTag\Item[]
     */
    public function getMetaTags($tagName, array $params = [])
    {
        if (!isset($params[0])) {
            throw new InvalidArgumentException(
                '$params[0]',
                'Literal URL handler requires the path to output.'
            );
        }

        $path = $params[0];
        $request = $this->requestStack->getCurrentRequest();

        if (!preg_match('/^https?:\/\//', $path) && $request instanceof Request) {
            $path = $request->getUriForPath('/' . ltrim($path, '/'));
        }

        return [
            new Item(
                $tagName,
                $path
            ),
        ];
    }
}
