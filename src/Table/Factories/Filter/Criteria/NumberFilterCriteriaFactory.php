<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Factories\Filter\Criteria;

use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;
use Patrikjak\Utils\Table\Contracts\Filter\FilterCriteriaFactory;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\NumberFilterCriteria;

final readonly class NumberFilterCriteriaFactory implements FilterCriteriaFactory
{
    /**
     * @param array<string, mixed> $data
     */
    public function make(string $column, array $data): AbstractFilterCriteria
    {
        $from = isset($data['from']) ? (float) $data['from'] : null;
        $to = isset($data['to']) ? (float) $data['to'] : null;

        return new NumberFilterCriteria($column, $from, $to);
    }
}
