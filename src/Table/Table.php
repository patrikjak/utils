<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table;

use Patrikjak\Utils\Table\Actions\ActionInterface;
use Patrikjak\Utils\Table\ColumnTypes\Interfaces\ColumnType;
use Patrikjak\Utils\Table\Pagination\PaginationSettings;

final readonly class Table
{
    /**
     * @param array<string, string> $header
     * @param array<array<scalar>> $data
     * @param array<string, ColumnType> $columns
     * @param array<ActionInterface> $actions
     */
    public function __construct(
        public string $tableId,
        public array $header,
        public array $data,
        public array $columns,
        public string $rowId,
        public bool $showCheckboxes,
        public bool $showOrder,
        public ?string $expandable,
        public array $actions,
        public ?PaginationSettings $paginationSettings = null,
    ) {
    }

    public function hasActions(): bool
    {
        return count($this->actions) > 0;
    }

    public function hasPagination(): bool
    {
        return $this->paginationSettings !== null;
    }
}