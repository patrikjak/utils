<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Services;

use Patrikjak\Utils\Table\Dto\Sort\SortableColumn;
use Patrikjak\Utils\Table\Dto\Sort\SortCriteria;

interface Sortable extends Renderable
{
    /**
     * @return array<SortableColumn>
     */
    public function getSortableColumns(): array;

    public function getSortCriteria(): ?SortCriteria;
}