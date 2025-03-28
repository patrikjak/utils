<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Services\QueryBuilder;

use Illuminate\Contracts\Database\Query\Builder;
use Patrikjak\Utils\Common\Dto\Sort\SortCriteria;

class SortService
{
    /**
     * @param array<string, string> $columnsMask
     */
    public function applySort(Builder $query, ?SortCriteria $criteria, array $columnsMask = []): void
    {
        if ($criteria === null) {
            return;
        }

        $column = $criteria->column;

        if ($columnsMask !== [] && in_array($criteria->column, $columnsMask, true)) {
            $column = array_search($criteria->column, $columnsMask, true);
        }

        $query->orderBy($column, $criteria->order->value);
    }
}