<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto;

use Patrikjak\Utils\Table\Dto\Pagination\Settings;
use Patrikjak\Utils\Table\Services\ColumnTypes\Interfaces\ColumnType;

final readonly class Table
{
    /**
     * @param array<string, string> $header
     * @param array<array<scalar>> $data
     * @param array<string, ColumnType> $columns
     * @param array<Action> $actions
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
        public ?Settings $paginationSettings = null,
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