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
    /**
     * @var \Netgen\Bundle\OpenGraphBundle\MetaTag\CollectorInterface
     */
    private $tagCollector;

    /**
     * @var \Netgen\Bundle\OpenGraphBundle\MetaTag\RendererInterface
     */
    private $tagRenderer;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var bool
     */
    private $throwExceptions = true;

    public function __construct(
        CollectorInterface $tagCollector,
        RendererInterface $tagRenderer,
        ?LoggerInterface $logger = null
    ) {
        $this->tagCollector = $tagCollector;
        $this->tagRenderer = $tagRenderer;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * Sets the flag that determines if the exceptions will thrown instead of logged.
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
                $this->getOpenGraphTags($content)
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
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Content $content
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
