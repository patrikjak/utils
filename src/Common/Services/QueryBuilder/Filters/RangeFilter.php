<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Services\QueryBuilder\Filters;

use Illuminate\Contracts\Database\Query\Builder;
use Patrikjak\Utils\Common\Dto\Filter\AbstractFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\DateFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\NumberFilterCriteria;

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
                $this->getRealColumn($filterCriteria->column, $columnsMask),
                '<=',
                $filterCriteria->to,
            );

            return;
        }

        if ($filterCriteria->to === null) {
            $query->orWhere(
                $this->getRealColumn($filterCriteria->column, $columnsMask),
                '>=',
                $filterCriteria->from,
            );

            return;
        }

        $query->orWhereBetween(
            $this->getRealColumn($filterCriteria->column, $columnsMask),
            [$filterCriteria->from, $filterCriteria->to],
        );
    }
}