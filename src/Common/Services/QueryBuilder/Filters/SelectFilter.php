<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Services\QueryBuilder\Filters;

use Illuminate\Contracts\Database\Query\Builder;
use Patrikjak\Utils\Common\Dto\Filter\AbstractFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\SelectFilterCriteria;

class SelectFilter extends AbstractFilter implements Filter
{
    /**
     * @inheritDoc
     */
    public function filter(Builder $query, AbstractFilterCriteria $filterCriteria, array $columnsMask = []): void
    {
        assert($filterCriteria instanceof SelectFilterCriteria);

        $query->orWhere(
            $this->getRealColumn($filterCriteria->column, $columnsMask),
            '=',
            $filterCriteria->value,
        );
    }
}