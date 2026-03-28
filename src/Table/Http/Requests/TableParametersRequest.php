<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Http\Requests;

use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Patrikjak\Utils\Common\Dto\Filter\AbstractFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\DateFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\FilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\JsonFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\NumberFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\SelectFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\TextFilterCriteria;
use Patrikjak\Utils\Common\Dto\Sort\SortCriteria;
use Patrikjak\Utils\Common\Enums\Filter\FilterType;
use Patrikjak\Utils\Common\Enums\Filter\JsonFilterType;
use Patrikjak\Utils\Common\Enums\Filter\TextFilterType;
use Patrikjak\Utils\Common\Enums\Sort\SortOrder;
use Patrikjak\Utils\Table\Dto\Parameters;

class TableParametersRequest extends FormRequest
{
    public function getTableParameters(string $tableId): Parameters
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

        $filters = [];

        foreach ($rawFilterCriteria as $column => $rawFilters) {
            foreach ($rawFilters as $rawFilterData) {
                $type = FilterType::tryFrom($rawFilterData['type']);

                if ($type === null) {
                    continue;
                }

                $filter = match ($type) {
                    FilterType::TEXT => $this->getTextFilterCriteria($rawFilterData, $column),
                    FilterType::SELECT => $this->getSelectFilterCriteria($rawFilterData, $column),
                    FilterType::DATE => $this->getDateFilterCriteria($rawFilterData, $column),
                    FilterType::NUMBER => $this->getNumberFilterCriteria($rawFilterData, $column),
                    FilterType::JSON => $this->getJsonFilterCriteria($rawFilterData, $column),
                };

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

    /**
     * @param array<string, mixed> $data
     */
    private function getTextFilterCriteria(array $data, string $column): ?AbstractFilterCriteria
    {
        if (!isset($data['operator'])) {
            return null;
        }

        $operator = TextFilterType::tryFrom($data['operator']);

        if ($operator === null) {
            return null;
        }

        return new TextFilterCriteria($column, $data['value'], $operator);
    }

    /**
     * @param array<string, string|int> $data
     */
    private function getSelectFilterCriteria(array $data, string $column): AbstractFilterCriteria
    {
        return new SelectFilterCriteria($column, $data['value']);
    }

    /**
     * @param array<string, string> $data
     */
    private function getDateFilterCriteria(array $data, string $column): AbstractFilterCriteria
    {
        $from = isset($data['from']) ? CarbonImmutable::make($data['from']) : null;
        $to = isset($data['to']) ? CarbonImmutable::make($data['to']) : null;

        return new DateFilterCriteria(
            $column,
            $from,
            $to,
        );
    }

    /**
     * @param array<string, string> $data
     */
    private function getNumberFilterCriteria(array $data, string $column): AbstractFilterCriteria
    {
        $from = isset($data['from']) ? (float) $data['from'] : null;
        $to = isset($data['to']) ? (float) $data['to'] : null;

        return new NumberFilterCriteria($column, $from, $to);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function getJsonFilterCriteria(array $data, string $column): ?AbstractFilterCriteria
    {
        if (!isset($data['operator'], $data['value'])) {
            return null;
        }

        $operator = JsonFilterType::tryFrom($data['operator']);

        if ($operator === null) {
            return null;
        }

        $jsonPath = $data['jsonPath'] ?: null;

        return new JsonFilterCriteria($column, $jsonPath, $data['value'], $operator);
    }
}
