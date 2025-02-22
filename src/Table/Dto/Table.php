<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto;

use Patrikjak\Utils\Table\Dto\BulkActions\Item as BulkActionItem;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;
use Patrikjak\Utils\Table\Dto\Filter\Settings as FilterSettings;
use Patrikjak\Utils\Table\Dto\Pagination\Settings;
use Patrikjak\Utils\Table\Dto\Sort\Settings as SortSettings;

final readonly class Table
{
    /**
     * @param array<string, string> $header
     * @param array<array<scalar>> $data
     * @param array<string> $columns
     * @param array<Item> $actions
     * @param array<BulkActionItem> $bulkActions
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
        public array $bulkActions = [],
        public ?string $htmlPartsUrl = null,
        public ?SortSettings $sortSettings = null,
        public ?FilterSettings $filterSettings = null,
    ) {
    }

    public function hasActions(): bool
    {
        return count($this->actions) > 0;
    }

    public function hasBulkActions(): bool
    {
        return count($this->bulkActions) > 0;
    }

    public function hasPagination(): bool
    {
        return $this->paginationSettings !== null;
    }

    public function isSortable(): bool
    {
        if ($this->sortSettings === null) {
            return false;
        }

        return count($this->sortSettings->sortableColumns) > 0;
    }

    public function isFilterable(): bool
    {
        if ($this->filterSettings === null) {
            return false;
        }


        return count($this->filterSettings->filterableColumns) > 0;
    }
}