<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Filter\Definitions;

class FilterableColumn
{
    public function __construct(public string $label, public string $column, public FilterDefinition $filterDefinition)
    {
    }
}