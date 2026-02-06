<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Table\Services\Implementations;

use Illuminate\Support\Collection;
use Patrikjak\Utils\Common\Dto\Filter\FilterCriteria;
use Patrikjak\Utils\Common\Dto\Sort\SortCriteria;
use Patrikjak\Utils\Table\Dto\BulkActions\Item as BulkItem;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\FilterableColumn;
use Patrikjak\Utils\Table\Dto\Pagination\LinkItem;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Dto\Sort\SortableColumn;
use Patrikjak\Utils\Table\Services\BasePaginatedTableProvider;
use Patrikjak\Utils\Table\Services\TableProviderInterface;

class JsonFilterTableProvider extends BasePaginatedTableProvider implements TableProviderInterface
{
    private string $tableId = 'json-table';

    /**
     * @var array<string>
     */
    private array $columns = ['id', 'name', 'metadata', 'data', 'tags', 'settings', 'preferences', 'contacts', 'users', 'matrix', 'json_data'];

    private string $rowId = 'id';

    private bool $showOrder = false;

    private bool $showCheckboxes = false;

    /**
     * @var array<Item>
     */
    private array $actions = [];

    /**
     * @var array<BulkItem>
     */
    private array $bulkActions = [];

    /**
     * @var array<int, int>
     */
    private array $paginationOptions = [10 => 10, 20 => 20];

    /**
     * @var array<SortableColumn>
     */
    private array $sortableColumns = [];

    private ?SortCriteria $sortCriteria = null;

    /**
     * @var array<FilterableColumn>
     */
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
            'metadata' => 'Metadata',
            'data' => 'Data',
            'tags' => 'Tags',
            'settings' => 'Settings',
            'preferences' => 'Preferences',
            'contacts' => 'Contacts',
            'users' => 'Users',
            'matrix' => 'Matrix',
            'json_data' => 'JSON Data',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->getPageData()->map(static function (array $item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
                'metadata' => $item['metadata'],
                'data' => $item['data'],
                'tags' => $item['tags'],
                'settings' => $item['settings'],
                'preferences' => $item['preferences'],
                'contacts' => $item['contacts'],
                'users' => $item['users'],
                'matrix' => $item['matrix'],
                'json_data' => $item['json_data'],
            ];
        })->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @inheritDoc
     */
    public function getRowId(): string
    {
        return $this->rowId;
    }

    /**
     * @inheritDoc
     */
    public function showOrder(): bool
    {
        return $this->showOrder;
    }

    /**
     * @inheritDoc
     */
    public function showCheckboxes(): bool
    {
        return $this->showCheckboxes;
    }

    /**
     * @inheritDoc
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @inheritDoc
     */
    public function getBulkActions(): array
    {
        return $this->bulkActions;
    }

    /**
     * @inheritDoc
     */
    public function getPaginationOptions(): array
    {
        return $this->paginationOptions;
    }

    /**
     * @inheritDoc
     */
    public function getSortableColumns(): array
    {
        return $this->sortableColumns;
    }

    public function setSortableColumns(array $sortableColumns): void
    {
        $this->sortableColumns = $sortableColumns;
    }

    public function getSortCriteria(): ?SortCriteria
    {
        return $this->sortCriteria;
    }

    public function setSortCriteria(?SortCriteria $sortCriteria): void
    {
        $this->sortCriteria = $sortCriteria;
    }

    /**
     * @inheritDoc
     */
    public function getFilterableColumns(): array
    {
        return $this->filterableColumns;
    }

    public function setFilterableColumns(array $filterableColumns): void
    {
        $this->filterableColumns = $filterableColumns;
    }

    public function getFilterCriteria(): ?FilterCriteria
    {
        return $this->filterCriteria;
    }

    public function setFilterCriteria(?FilterCriteria $filterCriteria): void
    {
        $this->filterCriteria = $filterCriteria;
    }

    protected function getPageData(): Collection
    {
        $data = collect([
            [
                'id' => 1,
                'name' => 'John Doe',
                'metadata' => '{"email": "john@example.com", "phone": "+420123456789"}',
                'data' => '{"address": {"city": "Prague", "country": "CZ"}, "status": "active"}',
                'tags' => '{"items": ["tech", "admin", "user"]}',
                'settings' => '{"theme": "dark", "notifications": true}',
                'preferences' => '{"language": "en", "timezone": "Europe/Prague"}',
                'contacts' => '{"list": [{"phones": ["+420123456", "+420987654"]}]}',
                'users' => '{"data": [{"profile": {"name": "John"}}, {"profile": {"name": "Jane"}}]}',
                'matrix' => '{"values": [["a", "b"], ["c", "d"]]}',
                'json_data' => '{"search_value": "found", "other": "data"}',
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'metadata' => '{"email": "jane@example.com", "phone": "+420987654321"}',
                'data' => '{"address": {"city": "Brno", "country": "CZ"}, "status": "inactive"}',
                'tags' => '{"items": ["user", "customer"]}',
                'settings' => '{"theme": "light", "notifications": false}',
                'preferences' => '{"language": "sk", "timezone": "Europe/Bratislava"}',
                'contacts' => '{"list": [{"phones": ["+421123456", "+421987654"]}]}',
                'users' => '{"data": [{"profile": {"name": "Alice"}}, {"profile": {"name": "Bob"}}]}',
                'matrix' => '{"values": [["1", "2"], ["3", "4"]]}',
                'json_data' => '{"config": "value", "search_value": "test"}',
            ],
            [
                'id' => 3,
                'name' => 'Admin User',
                'metadata' => '{"email": "admin@example.com", "phone": "+420555666777"}',
                'data' => '{"address": {"city": "Ostrava", "country": "CZ"}, "status": "active"}',
                'tags' => '{"items": ["admin", "tech", "support"]}',
                'settings' => '{"theme": "dark", "notifications": true}',
                'preferences' => '{"language": "en", "timezone": "UTC"}',
                'contacts' => '{"list": [{"phones": ["+420111222", "+420333444"]}]}',
                'users' => '{"data": [{"profile": {"name": "Charlie"}}, {"profile": {"name": "David"}}]}',
                'matrix' => '{"values": [["x", "y"], ["z", "w"]]}',
                'json_data' => '{"search_value": "admin_data", "type": "admin"}',
            ],
        ]);

        // Note: In real implementation, you would apply the JSON filter logic here
        // For integration tests, we're testing the UI rendering and structure

        return $data;
    }

    public function getTotal(): int
    {
        return $this->getPageData()->count();
    }

    public function getPaginator(): TablePaginator
    {
        $total = $this->getTotal();
        $currentPage = 1;
        $pageSize = 10;

        return new TablePaginator([
            new LinkItem('1', '1', true, false),
        ], $currentPage, $total, $pageSize, $pageSize);
    }
}
