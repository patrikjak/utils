<?php

namespace Patrikjak\Utils\Common\Dto;

use Illuminate\Support\Collection;
use Patrikjak\Utils\Common\Interfaces\Paginator as PaginatorInterface;

class Paginator implements PaginatorInterface
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