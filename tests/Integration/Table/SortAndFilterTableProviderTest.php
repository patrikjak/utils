<?php

namespace Patrikjak\Utils\Tests\Integration\Table;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Dto\Sort\SortableColumn;
use Patrikjak\Utils\Table\Dto\Sort\SortCriteria;
use Patrikjak\Utils\Table\Enums\Sort\SortOrder;
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

    public function testProviderHaveSortCriteria(): void
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
        $this->assertEquals($sortCriteria, $this->tableProvider->getSortCriteria());
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
