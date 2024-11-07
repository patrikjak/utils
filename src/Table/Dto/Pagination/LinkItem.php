<?php

namespace Patrikjak\Utils\Table\Dto\Pagination;

use Patrikjak\Utils\Table\Interfaces\Pagination\LinkItem as LinkItemInterface;

final readonly class LinkItem implements LinkItemInterface
{
    public function __construct(
        private string $label,
        private ?string $url,
        private bool $active,
    ) {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}