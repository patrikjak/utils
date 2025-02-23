<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Services\QueryBuilder\Filters;

use Illuminate\Database\Eloquent\Builder;
use Patrikjak\Utils\Common\Dto\Filter\AbstractFilterCriteria;

interface Filter
{
    /**
     * @param array<string, string> $columnsMask
     */
    public function filter(Builder $query, AbstractFilterCriteria $filterCriteria, array $columnsMask = []): void;
}