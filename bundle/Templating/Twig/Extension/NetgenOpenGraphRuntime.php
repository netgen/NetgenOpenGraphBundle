<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension;

use Exception;
use Ibexa\Contracts\Core\Repository\Values\Content\Content;
use Netgen\Bundle\OpenGraphBundle\MetaTag\CollectorInterface;
use Netgen\Bundle\OpenGraphBundle\MetaTag\RendererInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class NetgenOpenGraphRuntime
{
    private bool $throwExceptions = true;

    public function __construct(
        private CollectorInterface $tagCollector,
        private RendererInterface $tagRenderer,
        private ?LoggerInterface $logger = null,
    ) {
        $this->logger ??= new NullLogger();
    }

    /**
     * Sets the flag that determines if the exceptions will be thrown instead of logged.
     */
    public function setThrowExceptions(bool $throwExceptions = true): void
    {
        $this->throwExceptions = $throwExceptions;
    }

    /**
     * Renders Open Graph tags for provided content.
     */
    public function renderOpenGraphTags(Content $content): string
    {
        try {
            return $this->tagRenderer->render(
                $this->getOpenGraphTags($content),
            );
        } catch (Exception $e) {
            if ($this->throwExceptions) {
                throw $e;
            }

            $this->logger->error($e->getMessage());
        }

        return '';
    }

    /**
     * Returns Open Graph tags for provided content.
     *
     * @return \Netgen\Bundle\OpenGraphBundle\MetaTag\Item[]
     */
    public function getOpenGraphTags(Content $content): array
    {
        try {
            return $this->tagCollector->collect($content);
        } catch (Exception $e) {
            if ($this->throwExceptions) {
                throw $e;
            }

            $this->logger->error($e->getMessage());
        }

        return [];
    }
}
