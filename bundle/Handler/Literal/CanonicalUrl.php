<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler\Literal;

use Ibexa\Contracts\Core\Repository\Values\Content\Content as IbexaContent;
use Ibexa\Core\MVC\Symfony\Routing\UrlAliasRouter;
use Netgen\Bundle\OpenGraphBundle\Handler\HandlerInterface;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use Netgen\IbexaSiteApi\API\Values\Content as SiteApiContent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CanonicalUrl implements HandlerInterface
{
    private RequestStack $requestStack;

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(RequestStack $requestStack, UrlGeneratorInterface $urlGenerator)
    {
        $this->requestStack = $requestStack;
        $this->urlGenerator = $urlGenerator;
    }

    public function getMetaTags($tagName, array $params = []): array
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if (!$currentRequest instanceof Request) {
            return [];
        }

        $content = $currentRequest->attributes->get('content');
        if (!$content instanceof IbexaContent && !$content instanceof SiteApiContent) {
            return [];
        }

        if ($content->contentInfo->mainLocationId === null) {
            return [];
        }

        $value = $this->urlGenerator->generate(
            UrlAliasRouter::URL_ALIAS_ROUTE_NAME,
            [
                'locationId' => $content->contentInfo->mainLocationId,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );

        return [
            new Item(
                $tagName,
                $value,
            ),
        ];
    }
}
