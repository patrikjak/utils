<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Services\QueryBuilder;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Collection;
use Patrikjak\Utils\Common\Dto\Filter\AbstractFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\FilterCriteria;
use Patrikjak\Utils\Common\Enums\Filter\FilterType;
use Patrikjak\Utils\Common\Services\QueryBuilder\Filters\Filter;
use Patrikjak\Utils\Common\Services\QueryBuilder\Filters\JsonFilter;
use Patrikjak\Utils\Common\Services\QueryBuilder\Filters\RangeFilter;
use Patrikjak\Utils\Common\Services\QueryBuilder\Filters\SelectFilter;
use Patrikjak\Utils\Common\Services\QueryBuilder\Filters\TextFilter;

class FilterService
{
    /**
     * @param array<string> $searchableColumns
     * @param array<string, string> $columnsMask
     */
    public function applySearch(
        Builder $query,
        ?string $searchQuery,
        array $searchableColumns,
        array $columnsMask = [],
    ): void {
        if ($searchQuery === null || $searchQuery === '' || count($searchableColumns) === 0) {
            return;
        }

        $escapedValue = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $searchQuery);
        $likeValue = sprintf('%%%s%%', $escapedValue);

        $query->where(function (Builder $query) use ($searchableColumns, $likeValue, $columnsMask): void {
            foreach ($searchableColumns as $column) {
                $realColumn = $columnsMask !== [] && array_key_exists($column, $columnsMask)
                    ? $columnsMask[$column]
                    : $column;
                $query->orWhere($realColumn, 'like', $likeValue);
            }
        });
    }

    /**
     * @param array<string, string> $columnsMask
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
                    $filterStrategy = $this->getFilterStrategy($filter->getType());

                    $filterStrategy->filter($query, $filter, $columnsMask);
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

    private function getFilterStrategy(FilterType $filterType): Filter
    {
        return match ($filterType) {
            FilterType::TEXT => new TextFilter(),
            FilterType::SELECT => new SelectFilter(),
            FilterType::DATE, FilterType::NUMBER => new RangeFilter(),
            FilterType::JSON => new JsonFilter(),
        };
    }
}
