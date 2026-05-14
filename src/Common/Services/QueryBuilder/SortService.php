<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Services\QueryBuilder;

use Illuminate\Contracts\Database\Query\Builder;
use Patrikjak\Utils\Table\ValueObjects\Sort\SortCriteria;

class SortService
{
    use ResolvesColumnMask;

    /**
     * @param array<string, string> $columnsMask [displayColumn => realDatabaseColumn]
     */
    public function applySort(Builder $query, ?SortCriteria $criteria, array $columnsMask = []): void
    {
        if ($criteria === null) {
            return;
        }

        $query->orderBy($this->resolveColumn($criteria->column, $columnsMask), $criteria->order->value);
    }
}
