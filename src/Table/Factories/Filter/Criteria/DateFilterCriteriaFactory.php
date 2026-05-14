<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Factories\Filter\Criteria;

use Carbon\CarbonImmutable;
use Patrikjak\Utils\Table\Contracts\Filter\FilterCriteriaFactory;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\DateFilterCriteria;

final readonly class DateFilterCriteriaFactory implements FilterCriteriaFactory
{
    /**
     * @param array<string, mixed> $data
     */
    public function make(string $column, array $data): AbstractFilterCriteria
    {
        $from = isset($data['from']) ? CarbonImmutable::make($data['from']) : null;
        $to = isset($data['to']) ? CarbonImmutable::make($data['to']) : null;

        return new DateFilterCriteria($column, $from, $to);
    }
}
