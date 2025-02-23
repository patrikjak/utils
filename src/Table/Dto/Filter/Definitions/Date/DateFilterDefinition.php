<?php

namespace Patrikjak\Utils\Table\Dto\Filter\Definitions\Date;

use Carbon\CarbonInterface;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\FilterDefinition;
use Patrikjak\Utils\Table\Enums\Filter\FilterType;

class DateFilterDefinition implements FilterDefinition
{
    public function __construct(public ?CarbonInterface $from = null, public ?CarbonInterface $to = null)
    {
    }

    public function getType(): FilterType
    {
        return FilterType::DATE;
    }
}