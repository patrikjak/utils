<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Table\Services;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Services\TableProviderInterface;
use Patrikjak\Utils\Table\View\Table;
use Patrikjak\Utils\Tests\Integration\Table\Services\Implementations\FullTableSearchTableProvider;
use Patrikjak\Utils\Tests\Integration\Table\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class FullTableSearchTableProviderTest extends TestCase
{
    use MatchesSnapshots;

    private TableProviderInterface $tableProvider;

    public function testTableWithSearchableColumns(): void
    {
        $this->tableProvider->setSearchableColumns(['name', 'email']);

        $this->tableMatchesSnapshot();
    }

    public function testTableWithSearchQuery(): void
    {
        $this->tableProvider->setSearchableColumns(['name', 'email']);

        $this->tableMatchesSnapshot(new Parameters(1, 10, null, null, 'john'));
    }

    public function testProviderHasSearchQuery(): void
    {
        $this->tableProvider->setSearchableColumns(['name', 'email']);

        $this->tableMatchesSnapshot(new Parameters(1, 10, null, null, 'john'));
        $this->assertSame('john', $this->tableProvider->getSearchQuery());
    }

    public function testTableIsNotSearchableWithoutSearchableColumns(): void
    {
        $table = $this->tableProvider->getTable(new Parameters(1, 10, null, null));

        $this->assertFalse($table->isSearchable());
    }

    public function testTableIsSearchableWithSearchableColumns(): void
    {
        $this->tableProvider->setSearchableColumns(['name', 'email']);

        $table = $this->tableProvider->getTable(new Parameters(1, 10, null, null));

        $this->assertTrue($table->isSearchable());
    }

    public function testCanGetHtmlPartsWithSearch(): void
    {
        $this->tableProvider->setSearchableColumns(['name', 'email']);

        $htmlParts = $this->tableProvider->getHtmlParts(new Parameters(1, 10, null, null));

        $this->assertMatchesHtmlSnapshot($htmlParts['body']);
        $this->assertMatchesHtmlSnapshot($htmlParts['head']);
        $this->assertMatchesHtmlSnapshot($htmlParts['options']);
    }

    public function testSearchInputPreservesQueryOnRender(): void
    {
        $this->tableProvider->setSearchableColumns(['name', 'email']);

        $htmlParts = $this->tableProvider->getHtmlParts(new Parameters(1, 10, null, null, 'alice'));

        $this->assertStringContainsString('value="alice"', $htmlParts['options']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tableProvider = new FullTableSearchTableProvider();
    }

    private function tableMatchesSnapshot(?Parameters $parameters = null): void
    {
        $table = $this->tableProvider->getTable(
            $parameters ?? new Parameters(1, 10, null, null),
        );

        $view = Blade::render('<x-pjutils.table::table :$table />', ['table' => $table]);

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
