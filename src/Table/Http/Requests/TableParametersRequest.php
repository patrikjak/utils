<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Enums\Sort\SortOrder;
use Patrikjak\Utils\Table\Registry\FilterStrategyRegistry;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\FilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Sort\SortCriteria;

class TableParametersRequest extends FormRequest
{
    public function getTableParameters(): Parameters
    {
        return new Parameters(
            $this->getCurrentPage(),
            $this->getPageSize(),
            $this->getSortCriteria(),
            $this->getFilterCriteria(),
            $this->getSearchQuery(),
            $this->getVisibleColumns(),
        );
    }

    private function getCurrentPage(): int
    {
        return (int) ($this->input('page') ?? 1);
    }

    private function getPageSize(): int
    {
        return (int) ($this->input('pageSize') ?? 10);
    }

    private function getSortCriteria(): ?SortCriteria
    {
        $sortColumn = $this->input('sort');
        $order = $this->input('order');
        $deleteSort = $this->boolean('deleteSort');

        if ($deleteSort || $sortColumn === null || $order === null) {
            return null;
        }

        $order = SortOrder::tryFrom($order);

        if ($order === null) {
            return null;
        }

        return new SortCriteria($sortColumn, $order);
    }

    private function getFilterCriteria(): ?FilterCriteria
    {
        $rawFilterCriteria = $this->input('filter');
        $deleteFilters = $this->boolean('deleteFilters');

        if ($deleteFilters || $rawFilterCriteria === null) {
            return null;
        }

        $registry = app(FilterStrategyRegistry::class);
        $filters = [];

        foreach ($rawFilterCriteria as $column => $rawFilters) {
            foreach ($rawFilters as $rawFilterData) {
                $typeString = $rawFilterData['type'] ?? null;

                if ($typeString === null || !$registry->has($typeString)) {
                    continue;
                }

                $filter = $registry->getCriteriaFactory($typeString)->make($column, $rawFilterData);

                if ($filter === null) {
                    continue;
                }

                $filters[] = $filter;
            }
        }

        return new FilterCriteria($filters);
    }

    private function getSearchQuery(): ?string
    {
        $deleteSearch = $this->boolean('deleteSearch');

        if ($deleteSearch) {
            return null;
        }

        $searchQuery = $this->input('search');

        if ($searchQuery === null) {
            return null;
        }

        return (string) $searchQuery ?: null;
    }

    /**
     * @return array<string>|null
     */
    private function getVisibleColumns(): ?array
    {
        $raw = $this->input('visibleColumns');

        if ($raw === null) {
            return null;
        }

        if (!is_array($raw)) {
            return null;
        }

        $columns = array_values(
            array_filter(
                array_map('strval', $raw),
            ),
        );

        return $columns !== [] ? $columns : null;
    }
}
