<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Filter\Definitions;

use Patrikjak\Utils\Common\Enums\Filter\FilterType;

interface FilterDefinition
{
    public function getType(): FilterType;
}