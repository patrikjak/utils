<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Services\QueryBuilder\Filters;

use Illuminate\Contracts\Database\Query\Builder;
use InvalidArgumentException;
use Patrikjak\Utils\Table\Enums\Filter\JsonFilterType;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\JsonFilterCriteria;

class JsonFilter extends AbstractFilter implements Filter
{
    /**
     * @inheritDoc
     */
    public function filter(Builder $query, AbstractFilterCriteria $filterCriteria, array $columnsMask = []): void
    {
        if (!$filterCriteria instanceof JsonFilterCriteria) {
            throw new InvalidArgumentException('Expected JsonFilterCriteria instance');
        }

        if ($filterCriteria->value === null || $filterCriteria->value === '') {
            return;
        }

        $resolvedDatabaseColumn = $filterCriteria->getDatabaseColumn();

        $resolvedColumn = $resolvedDatabaseColumn ?? $this->resolveColumn($filterCriteria->column, $columnsMask);
        $column = $query->getGrammar()->wrap($resolvedColumn);
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

        if (preg_match('/^\$(\.[a-zA-Z_][a-zA-Z0-9_]*|\[\d+\])*$/', $normalizedPath) !== 1) {
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
        $escapedValue = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);

        return match ($jsonFilterType) {
            JsonFilterType::CONTAINS, JsonFilterType::NOT_CONTAINS => sprintf('%%%s%%', $escapedValue),
            JsonFilterType::STARTS_WITH => sprintf('%s%%', $escapedValue),
            JsonFilterType::ENDS_WITH => sprintf('%%%s', $escapedValue),
            JsonFilterType::EQUALS, JsonFilterType::NOT_EQUALS => $value,
        };
    }
}
