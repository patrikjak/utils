<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Factories\Filter\Criteria;

use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;
use Patrikjak\Utils\Table\Contracts\Filter\FilterCriteriaFactory;
use Patrikjak\Utils\Table\Enums\Filter\JsonFilterType;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\JsonFilterCriteria;

final readonly class JsonFilterCriteriaFactory implements FilterCriteriaFactory
{
    /**
     * @param array<string, mixed> $data
     */
    public function make(string $column, array $data): ?AbstractFilterCriteria
    {
        if (!isset($data['operator'], $data['value'])) {
            return null;
        }

        $operator = JsonFilterType::tryFrom($data['operator']);

        if ($operator === null) {
            return null;
        }

        $jsonPath = ($data['json-path'] ?? '') ?: null;

        return new JsonFilterCriteria($column, $jsonPath, $data['value'], $operator);
    }
}
