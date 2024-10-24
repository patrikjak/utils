<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Pagination;

use Illuminate\Support\Collection;

final readonly class PaginationSettings
{
    /**
     * @param array<int, int> $pageSizeOptions
     * @param Collection<int, array{url: string, label: string, active: bool}> $links
     */
    public function __construct(
        public int $page,
        public int $pageSize,
        public array $pageSizeOptions,
        public string $path,
        public Collection $links,
        public int $lastPage,
        public bool $isFirstPage,
        public bool $isLastPage,
    ) {
    }
}