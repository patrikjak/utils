<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Services\QueryBuilder\Filters;

use Illuminate\Contracts\Database\Query\Builder;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\SelectFilterCriteria;

class SelectFilter extends AbstractFilter implements Filter
{
    /**
     * @inheritDoc
     */
    public function filter(Builder $query, AbstractFilterCriteria $filterCriteria, array $columnsMask = []): void
    {
        assert($filterCriteria instanceof SelectFilterCriteria);

        $query->orWhere(
            $this->resolveColumn($filterCriteria->column, $columnsMask),
            '=',
            $filterCriteria->value,
        );
    }
}
