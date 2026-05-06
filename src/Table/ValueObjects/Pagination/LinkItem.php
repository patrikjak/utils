<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Pagination;

use Patrikjak\Utils\Table\Contracts\Pagination\LinkItem as LinkItemContract;

readonly class LinkItem implements LinkItemContract
{
    public function __construct(
        private string $label,
        private ?string $url,
        private bool $active = false,
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
