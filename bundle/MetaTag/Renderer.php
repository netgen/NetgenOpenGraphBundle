<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\MetaTag;

use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;

class Renderer implements RendererInterface
{
    public function render(array $metaTags = []): string
    {
        $html = '';

        foreach ($metaTags as $metaTag) {
            if (!$metaTag instanceof Item) {
                throw new InvalidArgumentException('metaTags', 'Cannot render meta tag, not an instance of \Netgen\Bundle\OpenGraphBundle\MetaTag\Item');
            }

            $tagName = htmlspecialchars($metaTag->getTagName(), ENT_QUOTES | ENT_SUBSTITUTE);
            $tagValue = htmlspecialchars($metaTag->getTagValue(), ENT_QUOTES | ENT_SUBSTITUTE);

            if (!empty($tagName) && !empty($tagValue)) {
                $html .= "<meta property=\"{$tagName}\" content=\"{$tagValue}\" />\n";
            }
        }

        return $html;
    }
}
