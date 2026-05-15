<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Table\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\FilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\SearchFilterCriteria;
use Patrikjak\Utils\Table\Builder\TableBuilder;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Tests\Integration\Table\Services\Implementations\FullTableSearchTableProvider;
use Patrikjak\Utils\Tests\Integration\Table\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

final class FullTableSearchTableProviderTest extends TestCase
{
    use MatchesSnapshots;

    private FullTableSearchTableProvider $tableProvider;

    /**
     * @throws BindingResolutionException
     */
    public function testTableWithSearchableColumns(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder->search('name', 'email');
        });

        $this->tableMatchesSnapshot();
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableWithSearchQuery(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder->search('name', 'email');
        });

        $this->tableMatchesSnapshot(new Parameters(
            1,
            10,
            null,
            new FilterCriteria([new SearchFilterCriteria('john', [])]),
        ));
    }

    /**
     * @throws BindingResolutionException
     */
    public function testProviderHasSearchCriteria(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder->search('name', 'email');
        });

        $parameters = new Parameters(
            1,
            10,
            null,
            new FilterCriteria([new SearchFilterCriteria('john', [])]),
        );

        $this->tableMatchesSnapshot($parameters);

        $lastParameters = $this->tableProvider->getLastParameters();
        $searchCriteria = collect($lastParameters?->filterCriteria?->filters ?? [])
            ->first(static fn (mixed $f) => $f instanceof SearchFilterCriteria);

        $this->assertInstanceOf(SearchFilterCriteria::class, $searchCriteria);
        $this->assertSame('john', $searchCriteria->value);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableIsNotSearchableWithoutSearchableColumns(): void
    {
        $table = $this->tableProvider->getTable(new Parameters(1, 10, null, null));

        $this->assertFalse($table->isSearchable());
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableIsSearchableWithSearchableColumns(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder->search('name', 'email');
        });

        $table = $this->tableProvider->getTable(new Parameters(1, 10, null, null));

        $this->assertTrue($table->isSearchable());
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanGetHtmlPartsWithSearch(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder->search('name', 'email');
        });

        $htmlParts = $this->tableProvider->getHtmlParts(new Parameters(1, 10, null, null));

        $this->assertMatchesHtmlSnapshot($htmlParts['body']);
        $this->assertMatchesHtmlSnapshot($htmlParts['head']);
        $this->assertMatchesHtmlSnapshot($htmlParts['options']);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSearchInputPreservesQueryOnRender(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder->search('name', 'email');
        });

        $htmlParts = $this->tableProvider->getHtmlParts(new Parameters(
            1,
            10,
            null,
            new FilterCriteria([new SearchFilterCriteria('alice', [])]),
        ));

        $this->assertStringContainsString('value="alice"', $htmlParts['options']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tableProvider = new FullTableSearchTableProvider();
    }

    /**
     * @throws BindingResolutionException
     */
    private function tableMatchesSnapshot(?Parameters $parameters = null): void
    {
        $table = $this->tableProvider->getTable(
            $parameters ?? new Parameters(1, 10, null, null),
        );

        $view = Blade::render('<x-pjutils.table::table :$table />', ['table' => $table]);

        $this->assertMatchesHtmlSnapshot($view);
    }
}
