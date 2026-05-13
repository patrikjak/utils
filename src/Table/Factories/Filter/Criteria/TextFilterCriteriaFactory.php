<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Factories\Filter\Criteria;

use Patrikjak\Utils\Table\Contracts\Filter\FilterCriteriaFactory;
use Patrikjak\Utils\Table\Enums\Filter\TextFilterType;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\TextFilterCriteria;

final readonly class TextFilterCriteriaFactory implements FilterCriteriaFactory
{
    /**
     * @param array<string, mixed> $data
     */
    public function make(string $column, array $data): ?AbstractFilterCriteria
    {
        if (!isset($data['operator'])) {
            return null;
        }

        $operator = TextFilterType::tryFrom($data['operator']);

        if ($operator === null) {
            return null;
        }

        return new TextFilterCriteria($column, $data['value'] ?? null, $operator);
    }
}
