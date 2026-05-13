<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\Number;

use Patrikjak\Utils\Table\Contracts\Filter\FilterDefinition;
use Patrikjak\Utils\Table\Contracts\Filter\RangeData;
use Patrikjak\Utils\Table\Enums\Filter\FilterType;

readonly class NumberFilterDefinition implements FilterDefinition, RangeData
{
    public function __construct(public ?float $min = null, public ?float $max = null)
    {
    }

    public function getType(): string
    {
        return FilterType::Number->value;
    }

    public function getMin(): ?string
    {
        if ($this->min === null) {
            return null;
        }

        return (string) $this->min;
    }

    public function getMax(): ?string
    {
        if ($this->max === null) {
            return null;
        }

        return (string) $this->max;
    }

    /**
     * @return array<string, string|int|float|null>
     */
    public function getFilterData(): array
    {
        return [
            'from' => $this->getMin(),
            'to' => $this->getMax(),
        ];
    }
}
