<?php

namespace Patrikjak\Utils\Table\Dto\Filter\Definitions;

use Patrikjak\Utils\Table\Dto\Filter\FilterType;

interface FilterDefinition
{
    public function getType(): FilterType;
}