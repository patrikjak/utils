<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Dto\Sort;

use Illuminate\Support\Collection;
use Patrikjak\Utils\Table\ValueObjects\Sort\SortableColumn;
use Patrikjak\Utils\Table\ValueObjects\Sort\SortCriteria;

readonly class Settings
{
    /**
     * @param Collection<int, SortableColumn> $sortableColumns
     */
    public function __construct(public Collection $sortableColumns, public ?SortCriteria $criteria = null)
    {
    }
}
