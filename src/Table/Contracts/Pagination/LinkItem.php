<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Contracts\Pagination;

interface LinkItem
{
    public function getLabel(): string;

    public function getUrl(): ?string;

    public function isActive(): bool;
}
