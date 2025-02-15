<?php

namespace Patrikjak\Utils\Tests\Integration\Table;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\BulkActions\Item as BulkActionItem;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Dto\Sort\SortableColumn;
use Patrikjak\Utils\Table\Dto\Sort\SortCriteria;
use Patrikjak\Utils\Table\Dto\Sort\SortOrder;
use Patrikjak\Utils\Table\Services\TableProviderInterface;
use Patrikjak\Utils\Table\View\Table;
use Patrikjak\Utils\Tests\Integration\Table\Implementations\SortAndFilterTableProvider;
use Spatie\Snapshots\MatchesSnapshots;

class SortAndFilterTableProviderTest extends TestCase
{
    use MatchesSnapshots;

    private TableProviderInterface $tableProvider;

    private Table $table;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tableProvider = new SortAndFilterTableProvider();
    }

    public function testTableCanBeRendered(): void
    {
        $this->tableMatchesSnapshot();
    }

    public function testTableWithCustomTableIdCanBeRendered(): void
    {
        $this->tableProvider->setTableId('customPaginatedTableId');

        $this->tableMatchesSnapshot();
    }

    public function testTableWithDifferentRowId(): void
    {
        $this->tableProvider->setRowId('email');

        $this->tableMatchesSnapshot();
    }

    public function testTableWithOrderDisplayedCanBeRendered(): void
    {
        $this->tableProvider->setShowOrder(true);

        $this->tableMatchesSnapshot();
    }

    public function testTableWithCheckboxesCanBeRendered(): void
    {
        $this->tableProvider->setShowCheckboxes(true);

        $this->tableMatchesSnapshot();
    }

    public function testTableWithDifferentColumnsDisplayed(): void
    {
        $this->tableProvider->setColumns(['id', 'name', 'email']);

        $this->tableMatchesSnapshot();
    }

    public function testTableWithActions(): void
    {
        $this->tableProvider->setActions([
            new Item('Edit', 'edit'),
            new Item('Delete', 'delete', type: Type::DANGER),
            new Item('Show', 'show', Icon::EYE),
            new Item('Hide', 'hide', Icon::EYE_SLASH, Type::DANGER),
        ]);

        $this->tableMatchesSnapshot();
    }

    public function testTableWithBulkActions(): void
    {
        $this->tableProvider->showCheckboxes();
        $this->tableProvider->setBulkActions([
            new BulkActionItem('Export', 'https://example.com/export'),
            new BulkActionItem('Delete', 'https://example.com/delete', 'DELETE', Icon::TRASH, Type::DANGER),
        ]);

        $this->tableMatchesSnapshot();
    }

    public function testTableWithCustomPaginationOptions(): void
    {
        $this->tableProvider->setPaginationOptions([5 => 5, 8 => 8, 10 => 10]);

        $this->tableMatchesSnapshot();
    }

    public function testTableWithSortableColumns(): void
    {
        $this->tableProvider->setSortableColumns([
            new SortableColumn('ID', 'id'),
            new SortableColumn('Name', 'name'),
            new SortableColumn('Email', 'email'),
            new SortableColumn('Created at', 'created_at'),
            new SortableColumn('Updated at', 'updated_at'),
        ]);

        $this->tableMatchesSnapshot();
    }

    public function testTableWithSortCriteria(): void
    {
        $this->tableProvider->setSortableColumns([
            new SortableColumn('ID', 'id'),
            new SortableColumn('Name', 'name'),
            new SortableColumn('Email', 'email'),
            new SortableColumn('Created at', 'created_at'),
            new SortableColumn('Updated at', 'updated_at'),
        ]);

        $sortCriteria = new SortCriteria('name', SortOrder::DESC);

        $this->tableProvider->setSortCriteria($sortCriteria);

        $this->tableMatchesSnapshot(new Parameters(1, 10, $sortCriteria));
    }

    public function testCanGetHtmlParts(): void
    {
        $this->tableProvider->setSortableColumns([
            new SortableColumn('ID', 'id'),
            new SortableColumn('Name', 'name'),
            new SortableColumn('Email', 'email'),
            new SortableColumn('Created at', 'created_at'),
            new SortableColumn('Updated at', 'updated_at'),
        ]);

        $htmlParts = $this->tableProvider->getHtmlParts(new Parameters(1, 10, null));

        $this->assertMatchesHtmlSnapshot($htmlParts['body']);
        $this->assertMatchesHtmlSnapshot($htmlParts['pagination']);
        $this->assertMatchesHtmlSnapshot($htmlParts['head']);
        $this->assertMatchesHtmlSnapshot($htmlParts['options']);
    }

    private function tableMatchesSnapshot(?Parameters $parameters = null): void
    {
        $table = $this->tableProvider->getTable($parameters ?? new Parameters(1, 10, null));
        
        $view = Blade::render('<x-pjutils.table::table :$table />', ['table' => $table]);

        $this->assertMatchesHtmlSnapshot($view);
    }
}
