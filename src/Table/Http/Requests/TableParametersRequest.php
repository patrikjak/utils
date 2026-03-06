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
    public function getTableParameters(): Parameters
    {
        return new Parameters(
            $this->getCurrentPage(),
            $this->getPageSize(),
            $this->getSortCriteriaFromRequest(),
            $this->getFilterCriteriaFromRequest(),
        );
    }

    private function getCurrentPage(): int
    {
        return $this->getPageFromRequest() ?? 1;
    }

    private function getPageSize(): int
    {
        return $this->getPageSizeFromRequest() ?? 10;
    }

    private function getPageFromRequest(): ?int
    {
        $page = $this->input('page');

        if ($page === null) {
            return null;
        }

        return (int) $page;
    }

    private function getPageSizeFromRequest(): ?int
    {
        $pageSize = $this->input('pageSize');

        if ($pageSize === null) {
            return null;
        }

        return (int) $pageSize;
    }

    private function getSortCriteriaFromRequest(): ?SortCriteria
    {
        $sortColumn = $this->input('sort');
        $order = $this->input('order');
        $deleteSort = $this->boolean('deleteSort');

        if ($deleteSort) {
            return null;
        }

        if ($order !== null) {
            $order = SortOrder::tryFrom($order);
        }

        if ($sortColumn === null || $order === null) {
            return null;
        }

        return new SortCriteria($sortColumn, $order);
    }

    private function getFilterCriteriaFromRequest(): ?FilterCriteria
    {
        $rawFilterCriteria = $this->input('filter');
        $deleteFilters = $this->boolean('deleteFilters');

        if ($deleteFilters) {
            return null;
        }

        if ($rawFilterCriteria === null) {
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
