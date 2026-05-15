<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Criteria;

use Illuminate\Contracts\Support\Arrayable;

abstract readonly class AbstractFilterCriteria implements Arrayable
{
    abstract public function getType(): string;

    /**
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;

    /**
     * Key-value pairs rendered as data-* attributes on the active filter chip.
     * Keys must match what the JS reads back to reconstruct the filter object.
     *
     * @return array<string, string|int|float|null>
     */
    abstract public function getFilterData(): array;

    public function __construct(public string $column)
    {
    }
}
