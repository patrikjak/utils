<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Http\Requests\Table;

use Illuminate\Foundation\Http\FormRequest;
use Patrikjak\Utils\Table\Parameters\TableParameters;
use stdClass;

class TableParametersRequest extends FormRequest
{
    private string $tableId;

    private bool $shouldUpdateCookie = false;

    public function getTableParameters(string $tableId): TableParameters
    {
        $this->tableId = $tableId;

        $parameters = new TableParameters($this->getCurrentPage(), $this->getPageSize());

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

    private function getDecodedParametersFromCookie(): ?stdClass
    {
        $cookie = $this->cookie($this->tableId);

        return $cookie ? json_decode($cookie) : null;
    }

    private function updateParametersCookie(TableParameters $parameters): void
    {
        $tableParameters = json_decode($this->cookie($this->tableId, '{}'), true);
        $updatedParameters = array_merge($tableParameters, $parameters->toArray());

        cookie()->queue(cookie($this->tableId, json_encode($updatedParameters), 60 * 24 * 30 * 12));
    }
}
