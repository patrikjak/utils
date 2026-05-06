<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Dto;

use Illuminate\Support\Collection;
use Patrikjak\Utils\Common\Contracts\Paginator as PaginatorInterface;

readonly class Paginator implements PaginatorInterface
{
    public function __construct(
        private readonly int $page,
        private readonly int $pageSize,
        private readonly Collection $data
    ) {
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getData(): Collection
    {
        return $this->data;
    }
}
