<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Dto;

use Illuminate\Support\Collection;
use Patrikjak\Utils\Table\Contracts\Cells\Cell as CellContract;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\SearchFilterCriteria;
use Patrikjak\Utils\Table\Dto\Filter\Settings as FilterSettings;
use Patrikjak\Utils\Table\Dto\Pagination\Settings;
use Patrikjak\Utils\Table\Dto\Sort\Settings as SortSettings;
use Patrikjak\Utils\Table\ValueObjects\BulkActions\Item as BulkActionItem;
use Patrikjak\Utils\Table\ValueObjects\Cells\Actions\Item;
use Patrikjak\Utils\Table\ValueObjects\ColumnVisibility;
use Patrikjak\Utils\Table\ValueObjects\EmptyState;

final readonly class Table
{
    /**
     * @param Collection<string, string> $header
     * @param Collection<int, array<string, string|int|CellContract>> $data
     * @param Collection<string, object|array<string, mixed>> $rawData
     * @param Collection<int, string> $columns
     * @param Collection<int, Item> $actions
     * @param Collection<int, BulkActionItem> $bulkActions
     */
    public function __construct(
        public string $tableId,
        public Collection $header,
        public Collection $data,
        public Collection $rawData,
        public Collection $columns,
        public string $rowId,
        public bool $showCheckboxes,
        public bool $showOrder,
        public ?string $expandable,
        public Collection $actions,
        public ?Settings $paginationSettings = null,
        public Collection $bulkActions = new Collection(),
        public ?string $htmlPartsUrl = null,
        public ?SortSettings $sortSettings = null,
        public ?FilterSettings $filterSettings = null,
        public ?int $defaultMaxLength = null,
        public bool $stickyHeader = false,
        public ?EmptyState $emptyState = null,
        public ?ColumnVisibility $columnVisibility = null,
        public ?Parameters $parameters = null,
    ) {
    }

    public function hasActions(): bool
    {
        return $this->actions->isNotEmpty();
    }

    public function hasDropdownActions(): bool
    {
        return $this->actions->contains(fn (Item $action) => !$action->inline);
    }

    /**
     * @return Collection<int, Item>
     */
    public function getDropdownActions(): Collection
    {
        return $this->actions->filter(fn (Item $action) => !$action->inline)->values();
    }

    public function hasBulkActions(): bool
    {
        return $this->bulkActions->isNotEmpty();
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

        return $this->sortSettings->sortableColumns->isNotEmpty();
    }

    public function isFilterable(): bool
    {
        if ($this->filterSettings === null) {
            return false;
        }

        return $this->filterSettings->filterableColumns->contains(
            static fn (mixed $col) => $col->column !== SearchFilterCriteria::COLUMN,
        );
    }

    public function isSearchable(): bool
    {
        if ($this->filterSettings === null) {
            return false;
        }

        return $this->filterSettings->filterableColumns->contains(
            static fn (mixed $col) => $col->column === SearchFilterCriteria::COLUMN,
        );
    }

    public function hasColumnVisibility(): bool
    {
        return $this->columnVisibility !== null;
    }
}
