<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Contracts\Filter;

use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;

interface FilterCriteriaFactory
{
    /**
     * @param array<string, mixed> $data
     */
    public function make(string $column, array $data): ?AbstractFilterCriteria;
}
