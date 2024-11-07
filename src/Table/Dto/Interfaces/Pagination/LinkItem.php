<?php

namespace Patrikjak\Utils\Table\Dto\Interfaces\Pagination;

interface LinkItem
{
    public function getLabel(): string;

    public function getUrl(): ?string;

    public function isActive(): bool;
}