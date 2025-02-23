<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Services\QueryBuilder\Filters;

use Illuminate\Database\Eloquent\Builder;
use Patrikjak\Utils\Common\Dto\Filter\AbstractFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\TextFilterCriteria;
use Patrikjak\Utils\Common\Enums\Filter\TextFilterType;

class TextFilter extends AbstractFilter implements Filter
{
    /**
     * @inheritDoc
     */
    public function filter(Builder $query, AbstractFilterCriteria $filterCriteria, array $columnsMask = []): void
    {
        assert($filterCriteria instanceof TextFilterCriteria);

        $query->orWhere(
            $this->getRealColumn($filterCriteria->column, $columnsMask),
            $this->getOperator($filterCriteria->filterType),
            $this->getConditionValue($filterCriteria->filterType, $filterCriteria->value),
        );
    }

    private function getOperator(TextFilterType $textFilterType): string
    {
        return match ($textFilterType) {
            TextFilterType::CONTAINS, TextFilterType::STARTS_WITH, TextFilterType::ENDS_WITH => 'like',
            TextFilterType::EQUALS => '=',
            TextFilterType::NOT_CONTAINS => 'not like',
            TextFilterType::NOT_EQUALS => '!=',
        };
    }

    private function getConditionValue(TextFilterType $textFilterType, string $value): string
    {
        return match ($textFilterType) {
            TextFilterType::CONTAINS, TextFilterType::NOT_CONTAINS => sprintf('%%%s%%', $value),
            TextFilterType::STARTS_WITH => sprintf('%s%%', $value),
            TextFilterType::ENDS_WITH => sprintf('%%%s', $value),
            default => $value,
        };
    }
}