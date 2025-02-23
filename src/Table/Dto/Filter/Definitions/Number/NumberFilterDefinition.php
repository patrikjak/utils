<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Filter\Definitions\Number;

use Patrikjak\Utils\Table\Dto\Filter\Definitions\FilterDefinition;
use Patrikjak\Utils\Common\Enums\Filter\FilterType;

class NumberFilterDefinition implements FilterDefinition
{
    public function __construct(public ?float $min = null, public ?float $max = null)
    {
    }

    public function getType(): FilterType
    {
        return FilterType::NUMBER;
    }
}