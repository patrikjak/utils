<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Services;

use Patrikjak\Utils\Table\Dto\BulkActions\Item as BulkActionItem;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Dto\Sort\SortableColumn;
use Patrikjak\Utils\Table\Dto\Table;

interface TableProviderInterface
{
    public function getTable(?Parameters $parameters = null): Table;

    public function getTableId(): string;

    /**
     * @return array<string, string>|null
     */
    public function getHeader(): ?array;

    /**
     * @return array<array<scalar>>
     */
    public function getData(): array;

    /** @return array<string> */
    public function getColumns(): array;

    public function getRowId(): string;

    public function showOrder(): bool;

    public function showCheckboxes(): bool;

    public function getExpandable(): ?string;

    /**
     * @return array<Item>
     */
    public function getActions(): array;

    /**
     * @return array<BulkActionItem>
     */
    public function getBulkActions(): array;

    /**
     * @return array<SortableColumn>
     */
    public function getSortableColumns(): array;
}