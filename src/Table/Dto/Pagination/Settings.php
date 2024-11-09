<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Pagination;

use Illuminate\Support\Collection;
use Patrikjak\Utils\Table\Interfaces\Pagination\LinkItem;

final readonly class Settings
{
    /**
     * @param array<int, int> $pageSizeOptions
     * @param Collection<LinkItem> $links
     */
    public function __construct(
        public int $page,
        public int $pageSize,
        public array $pageSizeOptions,
        public string $path,
        public Collection $links,
        public int $lastPage,
    ) {
    }
}