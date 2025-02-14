<?php

namespace Patrikjak\Utils\Table\Services;

use Patrikjak\Utils\Table\Dto\Sort\SortableColumn;

interface Sortable extends Renderable
{
    /**
     * @return array<SortableColumn>
     */
    public function getSortableColumns(): array;
}