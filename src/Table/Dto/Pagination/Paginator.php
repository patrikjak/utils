<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Pagination;

use Illuminate\Support\Collection;
use Patrikjak\Utils\Common\Dto\Paginator as BasePaginator;
use Patrikjak\Utils\Common\Interfaces\Paginator as BasePaginatorInterface;
use Patrikjak\Utils\Table\Interfaces\Pagination\Paginator as TablePaginatorInterface;

class Paginator extends BasePaginator implements BasePaginatorInterface, TablePaginatorInterface
{
    public function __construct(
        int $page,
        int $pageSize,
        Collection $data,
        private readonly string $path,
        private readonly int $lastPage,
        private readonly Collection $links,
    ) {
        parent::__construct($page, $pageSize, $data);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getLastPage(): int
    {
        return $this->lastPage;
    }

    /** @inheritDoc */
    public function getLinks(): Collection
    {
        return $this->links;
    }
}