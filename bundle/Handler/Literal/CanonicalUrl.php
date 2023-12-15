<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler\Literal;

use Ibexa\Core\MVC\Symfony\Routing\UrlAliasRouter;
use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

final class CanonicalUrl implements HandlerInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getMetaTags(string $tagName, array $params = []): array
    {
        $value = $this->router->generate(
            UrlAliasRouter::URL_ALIAS_ROUTE_NAME,
            [
                'locationId' => $this->content->contentInfo->mainLocationId,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );

        return [
            new Item(
                $tagName,
                $value
            ),
        ];
    }
}
