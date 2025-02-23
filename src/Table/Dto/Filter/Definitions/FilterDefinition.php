<?php

namespace Patrikjak\Utils\Table\Dto\Filter\Definitions;

use Patrikjak\Utils\Table\Enums\Filter\FilterType;

interface FilterDefinition
{
    public function getType(): FilterType;
}