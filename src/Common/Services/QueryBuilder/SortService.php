<?php

namespace Patrikjak\Utils\Common\Services\QueryBuilder;

use Illuminate\Contracts\Database\Query\Builder;
use Patrikjak\Utils\Table\Dto\Sort\SortCriteria;

class SortService
{
    public function applySort(Builder $query, ?SortCriteria $criteria, ?array $columnsMask = []): void
    {
        if ($criteria === null) {
            return;
        }

        $column = $criteria->column;

        if ($columnsMask !== [] && array_key_exists($criteria->column, $columnsMask)) {
            $column = $columnsMask[$criteria->column];
        }

        $query->orderBy($column, $criteria->order->value);
    }
}