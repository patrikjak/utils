<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Dto\Filter;

use Patrikjak\Utils\Common\Enums\Filter\FilterType;

class SelectFilterCriteria extends BaseFilterCriteria
{
    public function __construct(string $column, public string $value)
    {
        parent::__construct($column);
    }

    public function getType(): FilterType
    {
        return FilterType::SELECT;
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'column' => $this->column,
            'type' => $this->getType()->value,
            'value' => $this->value,
        ];
    }
}