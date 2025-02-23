<?php

namespace Patrikjak\Utils\Table\Dto\Filter\Criteria;

use Patrikjak\Utils\Table\Dto\Filter\FilterType;
use Patrikjak\Utils\Table\Enums\Filter\TextFilterType;

class TextFilterCriteria extends BaseFilterCriteria
{
    public function __construct(string $column, public ?string $value, public TextFilterType $filterType)
    {
        parent::__construct($column);
    }

    public function getType(): FilterType
    {
        return FilterType::TEXT;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            'column' => $this->column,
            'value' => $this->value,
            'operator' => $this->filterType->value,
            'type' => $this->getType()->value,
        ];
    }
}