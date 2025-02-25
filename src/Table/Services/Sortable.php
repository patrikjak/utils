<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Services;

use Patrikjak\Utils\Common\Dto\Sort\SortCriteria;
use Patrikjak\Utils\Table\Dto\Sort\SortableColumn;

interface Sortable extends Renderable
{
    /**
     * @return array<SortableColumn>
     */
    public function getSortableColumns(): array;

    public function getSortCriteria(): ?SortCriteria;
}