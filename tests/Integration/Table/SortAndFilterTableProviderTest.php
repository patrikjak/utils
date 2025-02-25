<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Table;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Common\Dto\Filter\DateFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\FilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\NumberFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\SelectFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\TextFilterCriteria;
use Patrikjak\Utils\Common\Dto\Sort\SortCriteria;
use Patrikjak\Utils\Common\Enums\Filter\TextFilterType;
use Patrikjak\Utils\Common\Enums\Sort\SortOrder;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\FilterableColumn;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Dto\Sort\SortableColumn;
use Patrikjak\Utils\Table\Factories\Filter\FilterableFactory;
use Patrikjak\Utils\Table\Services\TableProviderInterface;
use Patrikjak\Utils\Table\View\Table;
use Patrikjak\Utils\Tests\Integration\Table\Implementations\SortAndFilterTableProvider;
use Spatie\Snapshots\MatchesSnapshots;

class SortAndFilterTableProviderTest extends TestCase
{
    use MatchesSnapshots;

    private TableProviderInterface $tableProvider;

    private Table $table;

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

        $this->tableMatchesSnapshot(new Parameters(1, 10, $sortCriteria, null));
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

        $this->tableMatchesSnapshot(new Parameters(1, 10, $sortCriteria, null));
        $this->assertEquals($sortCriteria, $this->tableProvider->getSortCriteria());
    }

    public function testTableWithFilterableColumns(): void
    {
        $this->tableProvider->setFilterableColumns([
            new FilterableColumn('ID', 'id', FilterableFactory::text()),
            new FilterableColumn('Name', 'name', FilterableFactory::text()),
            new FilterableColumn('Email', 'email', FilterableFactory::select('http://example.com/data')),
            new FilterableColumn('Created at', 'created_at', FilterableFactory::date()),
            new FilterableColumn('Updated at', 'updated_at', FilterableFactory::date()),
            new FilterableColumn('Random number', 'random_number', FilterableFactory::number()),
        ]);

        $this->tableMatchesSnapshot();
    }

    public function testTableWithFilterCriteria(): void
    {
        $this->tableProvider->setFilterableColumns([
            new FilterableColumn('ID', 'id', FilterableFactory::text()),
            new FilterableColumn('Name', 'name', FilterableFactory::text()),
            new FilterableColumn('Email', 'email', FilterableFactory::select('http://example.com/data')),
            new FilterableColumn('Created at', 'created_at', FilterableFactory::date()),
            new FilterableColumn('Updated at', 'updated_at', FilterableFactory::date()),
            new FilterableColumn('Random number', 'random_number', FilterableFactory::number()),
        ]);

        $filterCriteria = new FilterCriteria([
            new TextFilterCriteria('name', 'John', TextFilterType::CONTAINS),
            new SelectFilterCriteria('email', 'example@email.com'),
            new DateFilterCriteria('created_at', CarbonImmutable::make('2024-12-01'), null),
            new DateFilterCriteria('updated_at', null, null),
            new NumberFilterCriteria('random_number', -5, 5),
        ]);

        $this->tableProvider->setFilterCriteria($filterCriteria);

        $this->tableMatchesSnapshot(new Parameters(1, 10, null, $filterCriteria));
    }

    public function testProviderHaveFilterCriteria(): void
    {
        $this->tableProvider->setFilterableColumns([
            new FilterableColumn('ID', 'id', FilterableFactory::text()),
            new FilterableColumn('Name', 'name', FilterableFactory::text()),
            new FilterableColumn('Email', 'email', FilterableFactory::select('http://example.com/data')),
            new FilterableColumn('Created at', 'created_at', FilterableFactory::date()),
            new FilterableColumn('Updated at', 'updated_at', FilterableFactory::date()),
            new FilterableColumn('Random number', 'random_number', FilterableFactory::number()),
        ]);

        $filterCriteria = new FilterCriteria([
            new TextFilterCriteria('name', 'John', TextFilterType::CONTAINS),
            new SelectFilterCriteria('email', 'example@email.com'),
            new DateFilterCriteria('created_at', CarbonImmutable::make('2024-12-01'), null),
            new DateFilterCriteria('updated_at', null, null),
            new NumberFilterCriteria('random_number', -5, 5),
        ]);

        $this->tableProvider->setFilterCriteria($filterCriteria);

        $this->tableMatchesSnapshot(new Parameters(1, 10, null, $filterCriteria));
        $this->assertEquals($filterCriteria, $this->tableProvider->getFilterCriteria());
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

        $this->tableProvider->setFilterableColumns([
            new FilterableColumn('ID', 'id', FilterableFactory::text()),
            new FilterableColumn('Name', 'name', FilterableFactory::text()),
            new FilterableColumn('Email', 'email', FilterableFactory::select('http://example.com/data')),
            new FilterableColumn('Created at', 'created_at', FilterableFactory::date()),
            new FilterableColumn('Updated at', 'updated_at', FilterableFactory::date()),
            new FilterableColumn('Random number', 'random_number', FilterableFactory::number()),
        ]);

        $htmlParts = $this->tableProvider->getHtmlParts(new Parameters(1, 10, null, null));

        $this->assertMatchesHtmlSnapshot($htmlParts['body']);
        $this->assertMatchesHtmlSnapshot($htmlParts['pagination']);
        $this->assertMatchesHtmlSnapshot($htmlParts['head']);
        $this->assertMatchesHtmlSnapshot($htmlParts['options']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tableProvider = new SortAndFilterTableProvider();
    }

    private function tableMatchesSnapshot(?Parameters $parameters = null): void
    {
        $table = $this->tableProvider->getTable(
            $parameters ?? new Parameters(1, 10, null, null),
        );
        
        $view = Blade::render('<x-pjutils.table::table :$table />', ['table' => $table]);

        $this->assertMatchesHtmlSnapshot($view);
    }
}
