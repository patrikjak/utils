<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Http\Requests;

use Illuminate\Cookie\CookieJar;
use Illuminate\Foundation\Http\FormRequest;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Dto\Sort\SortCriteria;
use Patrikjak\Utils\Table\Dto\Sort\SortOrder;
use stdClass;

class TableParametersRequest extends FormRequest
{
    private string $tableId;

    private bool $shouldUpdateCookie = false;

    private bool $checkSortCookie = true;

    public function getTableParameters(string $tableId): Parameters
    {
        $this->tableId = $tableId;

        $parameters = new Parameters($this->getCurrentPage(), $this->getPageSize(), $this->getSortCriteria());

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
}
