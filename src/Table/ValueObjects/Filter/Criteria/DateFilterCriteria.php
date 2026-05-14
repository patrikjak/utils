<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Criteria;

use Carbon\CarbonInterface;
use Patrikjak\Utils\Table\Enums\Filter\FilterType;

readonly class DateFilterCriteria extends AbstractFilterCriteria
{
    public function __construct(string $column, public ?CarbonInterface $from, public ?CarbonInterface $to)
    {
        parent::__construct($column);
    }

    public function getType(): string
    {
        return FilterType::Date->value;
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
            'type' => $this->getType(),
        ];
    }

    /**
     * @return array<string, string|null>
     */
    public function getFilterData(): array
    {
        return [
            'from' => $this->from?->format('Y-m-d'),
            'to' => $this->to?->format('Y-m-d'),
        ];
    }
}
