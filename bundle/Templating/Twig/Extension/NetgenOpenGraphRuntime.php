<?php

declare(strict_types=1);

namespace Netgen\Bundle\OpenGraphBundle\Templating\Twig\Extension;

use Exception;
use eZ\Publish\API\Repository\Values\Content\Content;
use Netgen\Bundle\OpenGraphBundle\MetaTag\CollectorInterface;
use Netgen\Bundle\OpenGraphBundle\MetaTag\RendererInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class NetgenOpenGraphRuntime
{
    /**
     * @var \Netgen\Bundle\OpenGraphBundle\MetaTag\CollectorInterface
     */
    protected $tagCollector;

    /**
     * @var \Netgen\Bundle\OpenGraphBundle\MetaTag\RendererInterface
     */
    protected $tagRenderer;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var bool
     */
    protected $throwExceptions = true;

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
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
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
