<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Http\Requests;

use Carbon\CarbonImmutable;
use Illuminate\Cookie\CookieJar;
use Illuminate\Foundation\Http\FormRequest;
use Patrikjak\Utils\Table\Dto\Filter\Criteria\BaseFilterCriteria;
use Patrikjak\Utils\Table\Dto\Filter\Criteria\DateFilterCriteria;
use Patrikjak\Utils\Table\Dto\Filter\Criteria\FilterCriteria;
use Patrikjak\Utils\Table\Dto\Filter\Criteria\NumberFilterCriteria;
use Patrikjak\Utils\Table\Dto\Filter\Criteria\SelectFilterCriteria;
use Patrikjak\Utils\Table\Dto\Filter\Criteria\TextFilterCriteria;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Dto\Sort\SortCriteria;
use Patrikjak\Utils\Table\Enums\Filter\FilterType;
use Patrikjak\Utils\Table\Enums\Filter\TextFilterType;
use Patrikjak\Utils\Table\Enums\Sort\SortOrder;
use stdClass;

class TableParametersRequest extends FormRequest
{
    private string $tableId;

    private bool $shouldUpdateCookie = false;

    private bool $checkSortCookie = true;

    private bool $checkFilterCookie = true;

    public function getTableParameters(string $tableId): Parameters
    {
        $this->tableId = $tableId;

        $parameters = new Parameters(
            $this->getCurrentPage(),
            $this->getPageSize(),
            $this->getSortCriteria(),
            $this->getFilterCriteria(),
        );

        if ($this->shouldUpdateCookie) {
            $this->updateParametersCookie($parameters);
        }

        return $parameters;
    }

    private function getCurrentPage(): int
    {
        return ($this->getPageFromRequest() ?? $this->getPageFromCookie()) ?? 1;
    }

    private function getPageSize(): int
    {
        return ($this->getPageSizeFromRequest() ?? $this->getPageSizeFromCookie()) ?? 10;
    }

    private function getSortCriteria(): ?SortCriteria
    {
        $sortCriteriaFromRequest = $this->getSortCriteriaFromRequest();

        if ($sortCriteriaFromRequest !== null) {
            return $sortCriteriaFromRequest;
        }

        if (!$this->checkSortCookie) {
            return null;
        }

        return $this->getSortCriteriaFromCookie() ?? null;
    }

    private function getFilterCriteria(): ?FilterCriteria
    {
        $filterCriteriaFromRequest = $this->getFilterCriteriaFromRequest();

        if ($filterCriteriaFromRequest !== null) {
            return $filterCriteriaFromRequest;
        }

        if (!$this->checkFilterCookie) {
            return null;
        }

        return $this->getFilterCriteriaFromCookie() ?? null;
    }

    private function getPageFromRequest(): ?int
    {
        $page = $this->input('page');

        if ($page === null) {
            return null;
        }

        $this->shouldUpdateCookie = true;

        return (int) $page;
    }

    private function getPageFromCookie(): ?int
    {
        return $this->getDecodedParametersFromCookie()?->page ?? null;
    }

    private function getPageSizeFromRequest(): ?int
    {
        $pageSize = $this->input('pageSize');

        if ($pageSize === null) {
            return null;
        }

        $this->shouldUpdateCookie = true;

        return (int) $pageSize;
    }

    private function getPageSizeFromCookie(): ?int
    {
        return $this->getDecodedParametersFromCookie()?->pageSize ?? null;
    }

    private function getSortCriteriaFromRequest(): ?SortCriteria
    {
        $sortColumn = $this->input('sort');
        $order = $this->input('order');
        $deleteSort = $this->boolean('deleteSort');

        if ($deleteSort) {
            $this->shouldUpdateCookie = true;
            $this->checkSortCookie = false;

            return null;
        }

        if ($order !== null) {
            $order = SortOrder::tryFrom($order);
        }

        if ($sortColumn === null || $order === null) {
            return null;
        }

        $this->shouldUpdateCookie = true;

        return new SortCriteria($sortColumn, $order);
    }

    private function getSortCriteriaFromCookie(): ?SortCriteria
    {
        $sortCriteria = $this->getDecodedParametersFromCookie()?->sortCriteria ?? null;

        if ($sortCriteria === null) {
            return null;
        }

        return new SortCriteria($sortCriteria->column, SortOrder::from($sortCriteria->order));
    }

    private function getFilterCriteriaFromRequest(): ?FilterCriteria
    {
        $rawFilterCriteria = $this->input('filter');
        $deleteFilters = $this->boolean('deleteFilters');

        if ($deleteFilters) {
            $this->shouldUpdateCookie = true;
            $this->checkFilterCookie = false;

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
                };

                if ($filter === null) {
                    continue;
                }

                $filters[] = $filter;
            }
        }

        $this->shouldUpdateCookie = true;

        return new FilterCriteria($filters);
    }

    private function getFilterCriteriaFromCookie(): ?FilterCriteria
    {
        $filters = [];
        $filterCriteria = $this->getDecodedParametersFromCookie()?->filterCriteria ?? null;

        if ($filterCriteria === null) {
            return null;
        }

        foreach ($filterCriteria as $criteria) {
            $type = FilterType::tryFrom($criteria->type);

            if ($type === null) {
                continue;
            }

            $filter = match ($type) {
                FilterType::TEXT => $this->getTextFilterCriteriaFromCookie($criteria),
                FilterType::SELECT => $this->getSelectFilterCriteriaFromCookie($criteria),
                FilterType::DATE => $this->getDateFilterCriteriaFromCookie($criteria),
                FilterType::NUMBER => $this->getNumberFilterCriteriaFromCookie($criteria),
            };

            if ($filter === null) {
                continue;
            }

            $filters[] = $filter;
        }

        return new FilterCriteria($filters);
    }

    private function getDecodedParametersFromCookie(): ?stdClass
    {
        $cookie = $this->cookie($this->tableId);

        return $cookie ? json_decode($cookie) : null;
    }

    private function updateParametersCookie(Parameters $parameters): void
    {
        $tableParameters = json_decode($this->cookie($this->tableId, '{}'), true);
        $updatedParameters = array_merge($tableParameters, $parameters->toArray());

        $cookieManager = app()->make(CookieJar::class);
        assert($cookieManager instanceof CookieJar);

        $cookieManager->queue(
            $this->tableId,
            json_encode($updatedParameters),
            60 * 24 * 365,
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    private function getTextFilterCriteria(array $data, string $column): ?BaseFilterCriteria
    {
        $operator = TextFilterType::tryFrom($data['operator']);

        if ($operator === null) {
            return null;
        }

        return new TextFilterCriteria($column, $data['value'], $operator);
    }

    private function getTextFilterCriteriaFromCookie(stdClass $filterData): ?BaseFilterCriteria
    {
        return $this->getTextFilterCriteria((array) $filterData, $filterData->column);
    }

    /**
     * @param array<string, string|int> $data
     */
    private function getSelectFilterCriteria(array $data, string $column): BaseFilterCriteria
    {
        return new SelectFilterCriteria($column, $data['value']);
    }

    private function getSelectFilterCriteriaFromCookie(stdClass $filterData): BaseFilterCriteria
    {
        return $this->getSelectFilterCriteria((array) $filterData, $filterData->column);
    }

    /**
     * @param array<string, string> $data
     */
    private function getDateFilterCriteria(array $data, string $column): BaseFilterCriteria
    {
        $from = isset($data['from']) ? CarbonImmutable::make($data['from']) : null;
        $to = isset($data['to']) ? CarbonImmutable::make($data['to']) : null;

        return new DateFilterCriteria(
            $column,
            $from,
            $to,
        );
    }

    private function getDateFilterCriteriaFromCookie(stdClass $filterData): BaseFilterCriteria
    {
        return $this->getDateFilterCriteria((array) $filterData, $filterData->column);
    }

    /**
     * @param array<string, string> $data
     */
    private function getNumberFilterCriteria(array $data, string $column): BaseFilterCriteria
    {
        $from = isset($data['from']) ? (float) $data['from'] : null;
        $to = isset($data['to']) ? (float) $data['to'] : null;

        return new NumberFilterCriteria($column, $from, $to);
    }

    private function getNumberFilterCriteriaFromCookie(stdClass $filterData): BaseFilterCriteria
    {
        return $this->getNumberFilterCriteria((array) $filterData, $filterData->column);
    }
}
