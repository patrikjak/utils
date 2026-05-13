<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Dto\Filter;

use Illuminate\Support\Collection;
use Patrikjak\Utils\Table\Contracts\Filter\NeedsDatabaseColumn;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\FilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\FilterableColumn;

readonly class Settings
{
    /**
     * @param Collection<int, FilterableColumn> $filterableColumns
     */
    public function __construct(public Collection $filterableColumns, public ?FilterCriteria $criteria)
    {
    }

    /**
     * Returns [displayColumn => realDatabaseColumn] for columns that have a databaseColumn mapping.
     * Pass this to FilterService::applyFilter() and FilterService::applySearch().
     *
     * @return array<string, string>
     */
    public function getColumnsMask(): array
    {
        return $this->filterableColumns
            ->filter(static fn (FilterableColumn $column) => $column->databaseColumn !== null)
            ->mapWithKeys(static fn (FilterableColumn $column) => [$column->column => $column->databaseColumn])
            ->all();
    }

    /**
     * Second enrichment pass: applies the per-column `databaseColumn` mappings that
     * were declared via `TableBuilder::column(..., databaseColumn: '...')`.
     *
     * Criteria that already carry a databaseColumn (set by the first pass in
     * `TableProvider::preEnrichParameters`) are left untouched, so the two passes
     * are safe to run in sequence without overwriting each other.
     */
    public function withResolvedDatabaseColumns(): self
    {
        if ($this->criteria === null) {
            return $this;
        }

        $mask = $this->getColumnsMask();

        if ($mask === []) {
            return $this;
        }

        return new self($this->filterableColumns, self::enrichCriteria($this->criteria, $mask));
    }

    /**
     * @param array<string, string> $mask [displayColumn => realDatabaseColumn]
     */
    public static function enrichCriteria(FilterCriteria $criteria, array $mask): FilterCriteria
    {
        $enriched = array_map(
            static function (mixed $filter) use ($mask): mixed {
                if (!$filter instanceof NeedsDatabaseColumn) {
                    return $filter;
                }

                $databaseColumn = $mask[$filter->column] ?? null;

                if ($databaseColumn === null || $filter->getDatabaseColumn() !== null) {
                    return $filter;
                }

                return $filter->withDatabaseColumn($databaseColumn);
            },
            $criteria->filters,
        );

        return new FilterCriteria($enriched);
    }
}
