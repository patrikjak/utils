<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Sort;

final readonly class Settings
{
    /**
     * @param array<SortableColumn> $sortableColumns
     */
    public function __construct(public array $sortableColumns, public ?SortCriteria $criteria = null)
    {
    }
}