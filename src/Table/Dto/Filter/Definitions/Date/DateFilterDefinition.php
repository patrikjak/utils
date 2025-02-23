<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Filter\Definitions\Date;

use Carbon\CarbonInterface;
use Patrikjak\Utils\Common\Enums\Filter\FilterType;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\FilterDefinition;

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