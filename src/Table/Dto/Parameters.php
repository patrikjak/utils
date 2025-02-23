<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto;

use Illuminate\Contracts\Support\Arrayable;
use Patrikjak\Utils\Table\Dto\Filter\Criteria\FilterCriteria;
use Patrikjak\Utils\Table\Dto\Sort\SortCriteria;

final readonly class Parameters implements Arrayable
{
    public function __construct(
        public int $page,
        public int $pageSize,
        public ?SortCriteria $sortCriteria,
        public ?FilterCriteria $filterCriteria,
    ) {
    }

    /**
     * @return array<string, int|string|null>
     */
    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'pageSize' => $this->pageSize,
            'sortCriteria' => $this->sortCriteria?->toArray(),
            'filterCriteria' => $this->filterCriteria?->toArray(),
        ];
    }
}