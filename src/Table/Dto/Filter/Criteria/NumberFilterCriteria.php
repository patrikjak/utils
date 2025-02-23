<?php

namespace Patrikjak\Utils\Table\Dto\Filter\Criteria;

use Patrikjak\Utils\Table\Enums\Filter\FilterType;

class NumberFilterCriteria extends BaseFilterCriteria
{
    public function __construct(string $column, public ?float $from, public ?float $to)
    {
        parent::__construct($column);
    }

    public function getType(): FilterType
    {
        return FilterType::NUMBER;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'column' => $this->column,
            'from' => $this->from,
            'to' => $this->to,
            'type' => $this->getType()->value,
        ];
    }
}