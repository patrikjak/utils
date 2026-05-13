<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\Date;

use Carbon\CarbonInterface;
use Patrikjak\Utils\Table\Contracts\Filter\FilterDefinition;
use Patrikjak\Utils\Table\Contracts\Filter\RangeData;
use Patrikjak\Utils\Table\Enums\Filter\FilterType;

readonly class DateFilterDefinition implements FilterDefinition, RangeData
{
    public function __construct(public ?CarbonInterface $from = null, public ?CarbonInterface $to = null)
    {
    }

    public function getType(): string
    {
        return FilterType::Date->value;
    }

    public function getMin(): ?string
    {
        return $this->from?->format('Y-m-d');
    }

    public function getMax(): ?string
    {
        return $this->to?->format('Y-m-d');
    }

    public function getFilterData(): array
    {
        return [
            'from' => $this->getMin(),
            'to' => $this->getMax(),
        ];
    }
}
