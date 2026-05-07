<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Contracts\Filter;

use Patrikjak\Utils\Common\Enums\Filter\FilterType;

interface FilterDefinition
{
    public function getType(): FilterType;
}
