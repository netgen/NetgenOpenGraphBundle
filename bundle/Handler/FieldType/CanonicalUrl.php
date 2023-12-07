<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Handler\FieldType;

use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Netgen\Bundle\OpenGraphBundle\MetaTag\Item;
use Symfony\Component\Routing\RouterInterface;

class CanonicalUrl extends Handler
{
    private RouterInterface $router;
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
    public function getMetaTags($tagName, array $params = []): array
    {
        $value = $this->router->generate(
            'ibexa.url.alias',
            [
                'locationId' => $this->content->contentInfo->mainLocationId,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );
        return array(
            new Item(
                $tagName,
                $value
            ),
        );
    }
    protected function supports(Field $field): bool
    {
        return true;
    }
}
