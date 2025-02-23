<?php

namespace Patrikjak\Utils\Table\Dto\Filter\Criteria;

final readonly class FilterCriteria
{
    /**
     * @param array<BaseFilterCriteria> $filters
     */
    public function __construct(public array $filters)
    {
    }

    /**
     * @return array<array<string|float>>
     */
    public function toArray(): array
    {
        return array_map(
            static fn (BaseFilterCriteria $filter) => $filter->toArray(),
            $this->filters
        );
    }
}