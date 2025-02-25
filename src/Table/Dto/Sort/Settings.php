<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Sort;

use Patrikjak\Utils\Common\Dto\Sort\SortCriteria;

final readonly class Settings
{
    /**
     * @param array<SortableColumn> $sortableColumns
     */
    public function __construct(public array $sortableColumns, public ?SortCriteria $criteria = null)
    {
    }
}