<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Filter\Definitions\Date;

use Carbon\CarbonInterface;
use Patrikjak\Utils\Common\Enums\Filter\FilterType;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\FilterDefinition;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\RangeData;

class DateFilterDefinition implements FilterDefinition, RangeData
{
    public function __construct(public ?CarbonInterface $from = null, public ?CarbonInterface $to = null)
    {
    }

    public function getType(): FilterType
    {
        return FilterType::DATE;
    }

    public function getMin(): ?string
    {
        return $this->from?->format('Y-m-d');
    }

    public function getMax(): ?string
    {
        return $this->to?->format('Y-m-d');
    }
}