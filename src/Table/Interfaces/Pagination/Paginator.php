<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Interfaces\Pagination;

use Illuminate\Support\Collection;
use Patrikjak\Utils\Common\Interfaces\Paginator as BasePaginator;

interface Paginator extends BasePaginator
{
    public function getPath(): string;

    public function getLastPage(): int;

    public function getData(): Collection;

    /** @return Collection<LinkItem> */
    public function getLinks(): Collection;
}