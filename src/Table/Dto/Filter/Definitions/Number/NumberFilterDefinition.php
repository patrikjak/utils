<?php

namespace Patrikjak\Utils\Table\Dto\Filter\Definitions\Number;

use Patrikjak\Utils\Table\Dto\Filter\Definitions\FilterDefinition;
use Patrikjak\Utils\Table\Dto\Filter\FilterType;

class NumberFilterDefinition implements FilterDefinition
{
    public function __construct(public ?int $min = null, public ?int $max = null)
    {
    }

    public function getType(): FilterType
    {
        return FilterType::NUMBER;
    }
}