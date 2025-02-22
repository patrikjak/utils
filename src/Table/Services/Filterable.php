<?php

namespace Patrikjak\Utils\Table\Services;

use Patrikjak\Utils\Table\Dto\Filter\Definitions\FilterableColumn;

interface Filterable
{
    /**
     * @return array<FilterableColumn>
     */
    public function getFilterableColumns(): array;
}