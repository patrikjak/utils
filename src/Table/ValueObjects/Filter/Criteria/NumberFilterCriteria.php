<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Criteria;

use Patrikjak\Utils\Table\Enums\Filter\FilterType;

readonly class NumberFilterCriteria extends AbstractFilterCriteria
{
    public function __construct(string $column, public ?float $from, public ?float $to)
    {
        parent::__construct($column);
    }

    public function getType(): string
    {
        return FilterType::Number->value;
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
            'type' => $this->getType(),
        ];
    }

    /**
     * @return array<string, float|null>
     */
    public function getFilterData(): array
    {
        return [
            'from' => $this->from,
            'to' => $this->to,
        ];
    }
}
