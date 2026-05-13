<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Factories\Filter\Criteria;

use Patrikjak\Utils\Table\Contracts\Filter\FilterCriteriaFactory;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\SelectFilterCriteria;

final readonly class SelectFilterCriteriaFactory implements FilterCriteriaFactory
{
    /**
     * @param array<string, mixed> $data
     */
    public function make(string $column, array $data): AbstractFilterCriteria
    {
        return new SelectFilterCriteria($column, (string) ($data['value'] ?? ''));
    }
}
