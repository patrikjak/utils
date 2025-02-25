<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Dto\Filter;

final readonly class FilterCriteria
{
    /**
     * @param array<AbstractFilterCriteria> $filters
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
            static fn (AbstractFilterCriteria $filter) => $filter->toArray(),
            $this->filters
        );
    }
}