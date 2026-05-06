<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Contracts;

use Patrikjak\Utils\Common\ValueObjects\Sort\SortCriteria;
use Patrikjak\Utils\Table\ValueObjects\Sort\SortableColumn;

interface Sortable extends Renderable
{
    /**
     * @return array<SortableColumn>
     */
    public function getSortableColumns(): array;

    public function getSortCriteria(): ?SortCriteria;
}
