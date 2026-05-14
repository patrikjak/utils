<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Contracts\Filter;

use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;

interface FilterCriteriaFactory
{
    /**
     * Returns null when data is malformed/insufficient. Implementations that never return null
     * may declare a non-nullable return type (LSP covariance is allowed).
     *
     * @param array<string, mixed> $data
     */
    public function make(string $column, array $data): ?AbstractFilterCriteria;
}
