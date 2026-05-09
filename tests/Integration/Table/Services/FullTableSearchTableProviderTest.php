<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Table\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Blade;
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

        $this->tableMatchesSnapshot(new Parameters(1, 10, null, null, 'john'));
    }

    /**
     * @throws BindingResolutionException
     */
    public function testProviderHasSearchQuery(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder->search('name', 'email');
        });

        $this->tableMatchesSnapshot(new Parameters(1, 10, null, null, 'john'));
        $this->assertSame('john', $this->tableProvider->getLastParameters()?->searchQuery);
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

        $htmlParts = $this->tableProvider->getHtmlParts(new Parameters(1, 10, null, null, 'alice'));

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
