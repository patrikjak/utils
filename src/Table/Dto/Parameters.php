<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Dto;

use Illuminate\Contracts\Support\Arrayable;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\FilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Sort\SortCriteria;

readonly class Parameters implements Arrayable
{
    /**
     * @param array<string>|null $visibleColumns
     */
    public function __construct(
        public int $page,
        public int $pageSize,
        public ?SortCriteria $sortCriteria,
        public ?FilterCriteria $filterCriteria,
        public ?string $searchQuery = null,
        public ?array $visibleColumns = null,
    ) {
    }

    public function withFilterCriteria(?FilterCriteria $filterCriteria): self
    {
        return new self(
            $this->page,
            $this->pageSize,
            $this->sortCriteria,
            $filterCriteria,
            $this->searchQuery,
            $this->visibleColumns,
        );
    }

    /**
     * @return array<string, array<array<float|string>|string>|int|string|null>
     */
    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'pageSize' => $this->pageSize,
            'sortCriteria' => $this->sortCriteria?->toArray(),
            'filterCriteria' => $this->filterCriteria?->toArray(),
            'searchQuery' => $this->searchQuery,
            'visibleColumns' => $this->visibleColumns,
        ];
    }
}
