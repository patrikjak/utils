<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Dto\Filter;

use Patrikjak\Utils\Common\Enums\Filter\FilterType;

class NumberFilterCriteria extends AbstractFilterCriteria
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