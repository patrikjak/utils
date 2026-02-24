<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Services\QueryBuilder\Filters;

use Illuminate\Contracts\Database\Query\Builder;
use InvalidArgumentException;
use Patrikjak\Utils\Common\Dto\Filter\AbstractFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\JsonFilterCriteria;
use Patrikjak\Utils\Common\Enums\Filter\JsonFilterType;

class JsonFilter extends AbstractFilter implements Filter
{
    /**
     * @inheritDoc
     */
    public function filter(Builder $query, AbstractFilterCriteria $filterCriteria, array $columnsMask = []): void
    {
        assert($filterCriteria instanceof JsonFilterCriteria);

        if ($filterCriteria->value === null || $filterCriteria->value === '') {
            return;
        }

        $column = $this->getRealColumn($filterCriteria->column, $columnsMask);
        $jsonPath = $this->buildJsonPath($filterCriteria->jsonPath);

        $operator = $this->getOperator($filterCriteria->filterType);
        $value = $this->getConditionValue($filterCriteria->filterType, $filterCriteria->value);

        $query->orWhereRaw(
            sprintf('JSON_UNQUOTE(JSON_EXTRACT(%s, ?)) %s ?', $column, $operator),
            [$jsonPath, $value],
        );
    }

    private function buildJsonPath(?string $jsonPath): string
    {
        if ($jsonPath === null || $jsonPath === '') {
            return '$';
        }

        $normalizedPath = str_starts_with($jsonPath, '$.') ? $jsonPath : '$.' . $jsonPath;

        if (preg_match('/^(\$\.)?[a-zA-Z0-9_.\[\]*]+$/', $normalizedPath) !== 1) {
            throw new InvalidArgumentException('Invalid JSON path format');
        }

        return $normalizedPath;
    }

    private function getOperator(JsonFilterType $jsonFilterType): string
    {
        return match ($jsonFilterType) {
            JsonFilterType::CONTAINS, JsonFilterType::STARTS_WITH, JsonFilterType::ENDS_WITH => 'like',
            JsonFilterType::EQUALS => '=',
            JsonFilterType::NOT_CONTAINS => 'not like',
            JsonFilterType::NOT_EQUALS => '!=',
        };
    }

    private function getConditionValue(JsonFilterType $jsonFilterType, string $value): string
    {
        return match ($jsonFilterType) {
            JsonFilterType::CONTAINS, JsonFilterType::NOT_CONTAINS => sprintf('%%%s%%', $value),
            JsonFilterType::STARTS_WITH => sprintf('%s%%', $value),
            JsonFilterType::ENDS_WITH => sprintf('%%%s', $value),
            default => $value,
        };
    }
}
