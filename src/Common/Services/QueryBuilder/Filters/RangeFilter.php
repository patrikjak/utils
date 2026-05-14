<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Services\QueryBuilder\Filters;

use Illuminate\Contracts\Database\Query\Builder;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\DateFilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\NumberFilterCriteria;

class RangeFilter extends AbstractFilter implements Filter
{
    /**
     * @inheritDoc
     */
    public function filter(Builder $query, AbstractFilterCriteria $filterCriteria, array $columnsMask = []): void
    {
        assert($filterCriteria instanceof DateFilterCriteria || $filterCriteria instanceof NumberFilterCriteria);

        if ($filterCriteria->from === null && $filterCriteria->to === null) {
            return;
        }

        if ($filterCriteria->from === null) {
            $query->orWhere(
                $this->resolveColumn($filterCriteria->column, $columnsMask),
                '<=',
                $filterCriteria->to,
            );

            return;
        }

        if ($filterCriteria->to === null) {
            $query->orWhere(
                $this->resolveColumn($filterCriteria->column, $columnsMask),
                '>=',
                $filterCriteria->from,
            );

            return;
        }

        $query->orWhereBetween(
            $this->resolveColumn($filterCriteria->column, $columnsMask),
            [$filterCriteria->from, $filterCriteria->to],
        );
    }
}
