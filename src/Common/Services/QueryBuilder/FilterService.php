<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Services\QueryBuilder;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Collection;
use Patrikjak\Utils\Table\Registry\FilterStrategyRegistry;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\FilterCriteria;

class FilterService
{
    use ResolvesColumnMask;

    public function __construct(private readonly FilterStrategyRegistry $filterStrategyRegistry)
    {
    }

    /**
     * @param array<string, string> $columnsMask [displayColumn => realDatabaseColumn]
     */
    public function applyFilter(Builder $query, ?FilterCriteria $filterCriteria, array $columnsMask = []): void
    {
        if ($filterCriteria === null) {
            return;
        }

        $groupedFilters = $this->getGroupedByColumns(new Collection($filterCriteria->filters));

        foreach ($groupedFilters as $filters) {
            $query->where(function (Builder $query) use ($filters, $columnsMask): void {
                foreach ($filters as $filter) {
                    $this->filterStrategyRegistry->get($filter->getType())->filter($query, $filter, $columnsMask);
                }
            });
        }
    }

    /**
     * @param Collection<AbstractFilterCriteria> $filters
     * @return Collection<string, Collection<AbstractFilterCriteria>>
     */
    private function getGroupedByColumns(Collection $filters): Collection
    {
        return $filters->groupBy(static function (AbstractFilterCriteria $filter) {
            return $filter->column;
        });
    }
}
