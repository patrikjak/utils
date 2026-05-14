<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Criteria;

use Patrikjak\Utils\Table\Enums\Filter\FilterType;

readonly class SelectFilterCriteria extends AbstractFilterCriteria
{
    public function __construct(string $column, public string $value)
    {
        parent::__construct($column);
    }

    public function getType(): string
    {
        return FilterType::Select->value;
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'column' => $this->column,
            'type' => $this->getType(),
            'value' => $this->value,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function getFilterData(): array
    {
        return ['value' => $this->value];
    }
}
