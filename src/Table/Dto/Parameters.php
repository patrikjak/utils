<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Dto;

use Illuminate\Contracts\Support\Arrayable;
use Patrikjak\Utils\Common\ValueObjects\Filter\FilterCriteria;
use Patrikjak\Utils\Common\ValueObjects\Sort\SortCriteria;

final readonly class Parameters implements Arrayable
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
