<?php

namespace Patrikjak\Utils\Table\Dto\Filter\Criteria;

use Carbon\CarbonInterface;
use Patrikjak\Utils\Table\Dto\Filter\FilterType;

class DateFilterCriteria extends BaseFilterCriteria
{
    public function __construct(string $column, public ?CarbonInterface $from, public ?CarbonInterface $to)
    {
        parent::__construct($column);
    }

    public function getType(): FilterType
    {
        return FilterType::DATE;
    }

    public function getFormattedFrom(): ?string
    {
        return $this->from?->format('d/m/Y');
    }

    public function getFormattedTo(): ?string
    {
        return $this->to?->format('d/m/Y');
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'column' => $this->column,
            'from' => $this->from?->format('Y-m-d'),
            'to' => $this->to?->format('Y-m-d'),
            'type' => $this->getType()->value,
        ];
    }
}