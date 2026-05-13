<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Contracts\Filter;

use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;

interface FilterCriteriaFactory
{
    /**
     * Build a filter criteria object from raw request data.
     *
     * Returns null when the data is malformed or insufficient to construct a meaningful
     * criteria (e.g. a required field is missing). Implementations that always produce a
     * valid criteria may declare a non-nullable return type (LSP covariance is allowed).
     *
     * @param array<string, mixed> $data
     */
    public function make(string $column, array $data): ?AbstractFilterCriteria;
}
