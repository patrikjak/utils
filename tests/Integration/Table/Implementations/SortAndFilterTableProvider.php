<?php

namespace Patrikjak\Utils\Tests\Integration\Table\Implementations;

use Illuminate\Support\Collection;
use Patrikjak\Utils\Common\Dto\Filter\FilterCriteria;
use Patrikjak\Utils\Common\Dto\Sort\SortCriteria;
use Patrikjak\Utils\Table\Dto\Pagination\LinkItem;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Services\BasePaginatedTableProvider;
use Patrikjak\Utils\Table\Services\TableProviderInterface;

class SortAndFilterTableProvider extends BasePaginatedTableProvider implements TableProviderInterface
{
    use TableProviderData;

    private string $tableId = 'table';

    private array $columns = ['id', 'name', 'email', 'created_at', 'updated_at'];

    private string $rowId = 'id';

    private bool $showOrder = false;

    private bool $showCheckboxes = false;

    private array $actions = [];

    private array $bulkActions = [];

    private array $paginationOptions = [10 => 10, 20 => 20, 50 => 50, 100 => 100];

    private array $sortableColumns = [];

    private ?SortCriteria $sortCriteria = null;

    private array $filterableColumns = [];

    private ?FilterCriteria $filterCriteria = null;

    public function getTableId(): string
    {
        return $this->tableId;
    }

    /** @inheritDoc */
    public function getHeader(): ?array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ];
    }

    public function getData(): array
    {
        return $this->getPageData()->map(function (array $user) {
            return [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'created_at' => $user['created_at'],
                'updated_at' => $user['updated_at'],
            ];
        })->toArray();
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getRowId(): string
    {
        return $this->rowId;
    }

    public function showOrder(): bool
    {
        return $this->showOrder;
    }

    public function showCheckboxes(): bool
    {
        return $this->showCheckboxes;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function getBulkActions(): array
    {
        return $this->bulkActions;
    }

    public function getSortableColumns(): array
    {
        return $this->sortableColumns;
    }

    public function getFilterableColumns(): array
    {
        return $this->filterableColumns;
    }

    protected function getPaginator(): TablePaginator
    {
        return new TablePaginator(
            1,
            10,
            new Collection($this->getTableData()),
            'https://example.com/table',
            1,
            new Collection([
                new LinkItem(__('pagination.previous'), null),
                new LinkItem('1', 'https://example.com/table/1', true),
                new LinkItem('2', 'https://example.com/table/2'),
                new LinkItem('3', 'https://example.com/table/3'),
                new LinkItem('4', 'https://example.com/table/4'),
                new LinkItem(__('pagination.next'), null),
            ]),
        );
    }

    /** @inheritDoc */
    protected function getPageSizeOptions(): array
    {
        return $this->paginationOptions;
    }

    public function setSortableColumns(array $sortableColumns): void
    {
        $this->sortableColumns = $sortableColumns;
    }

    public function setSortCriteria(?SortCriteria $sortCriteria): void
    {
        $this->sortCriteria = $sortCriteria;
    }

    public function setFilterableColumns(array $filterableColumns): void
    {
        $this->filterableColumns = $filterableColumns;
    }

    public function setFilterCriteria(?FilterCriteria $filterCriteria): void
    {
        $this->filterCriteria = $filterCriteria;
    }
}