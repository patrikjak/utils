<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Dto\Filter;

use Patrikjak\Utils\Common\Enums\Filter\FilterType;
use Patrikjak\Utils\Common\Enums\Filter\TextFilterType;

class TextFilterCriteria extends AbstractFilterCriteria
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