<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Services;

interface Searchable extends Renderable
{
    /**
     * @return array<string>
     */
    public function getSearchableColumns(): array;

    public function getSearchQuery(): ?string;
}
