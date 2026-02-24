<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Table\Services;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Common\Dto\Filter\FilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\JsonFilterCriteria;
use Patrikjak\Utils\Common\Enums\Filter\JsonFilterType;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\FilterableColumn;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Factories\Filter\FilterableFactory;
use Patrikjak\Utils\Table\Services\TableProviderInterface;
use Patrikjak\Utils\Table\View\Table;
use Patrikjak\Utils\Tests\Integration\Table\Services\Implementations\JsonFilterTableProvider;
use Patrikjak\Utils\Tests\Integration\Table\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class JsonFilterTableProviderTest extends TestCase
{
    use MatchesSnapshots;

    private TableProviderInterface $tableProvider;

    private Table $table;

    public function testTableWithJsonFilterableColumns(): void
    {
        $this->tableProvider->setFilterableColumns([
            new FilterableColumn('User Email', 'metadata', FilterableFactory::json('email')),
            new FilterableColumn('User Phone', 'metadata', FilterableFactory::json('phone')),
            new FilterableColumn('Address City', 'data', FilterableFactory::json('address.city')),
            new FilterableColumn('First Tag', 'tags', FilterableFactory::json('items[0]')),
            new FilterableColumn('User Settings', 'settings', FilterableFactory::json('theme')),
            new FilterableColumn('Full JSON', 'preferences', FilterableFactory::json()),
        ]);

        $this->tableMatchesSnapshot();
    }

    public function testTableWithJsonFilterCriteria(): void
    {
        $this->tableProvider->setFilterableColumns([
            new FilterableColumn('User Email', 'metadata', FilterableFactory::json('email')),
            new FilterableColumn('User Phone', 'metadata', FilterableFactory::json('phone')),
            new FilterableColumn('Address City', 'data', FilterableFactory::json('address.city')),
            new FilterableColumn('First Tag', 'tags', FilterableFactory::json('items[0]')),
            new FilterableColumn('Settings Theme', 'settings', FilterableFactory::json('theme')),
        ]);

        $filterCriteria = new FilterCriteria([
            new JsonFilterCriteria('metadata', 'email', 'john@example.com', JsonFilterType::CONTAINS),
            new JsonFilterCriteria('metadata', 'phone', '+420', JsonFilterType::STARTS_WITH),
            new JsonFilterCriteria('data', 'address.city', 'Prague', JsonFilterType::EQUALS),
            new JsonFilterCriteria('tags', 'items[0]', 'tech', JsonFilterType::CONTAINS),
            new JsonFilterCriteria('settings', 'theme', 'dark', JsonFilterType::EQUALS),
        ]);

        $this->tableProvider->setFilterCriteria($filterCriteria);

        $this->tableMatchesSnapshot(new Parameters(1, 10, null, $filterCriteria));
    }

    public function testJsonFilterWithDifferentOperators(): void
    {
        $this->tableProvider->setFilterableColumns([
            new FilterableColumn('Email', 'metadata', FilterableFactory::json('email')),
            new FilterableColumn('Status', 'data', FilterableFactory::json('status')),
        ]);

        $filterCriteria = new FilterCriteria([
            // Test all JSON filter types
            new JsonFilterCriteria('metadata', 'email', 'john', JsonFilterType::CONTAINS),
            new JsonFilterCriteria('metadata', 'email', 'spam', JsonFilterType::NOT_CONTAINS),
            new JsonFilterCriteria('data', 'status', 'active', JsonFilterType::EQUALS),
            new JsonFilterCriteria('data', 'status', 'inactive', JsonFilterType::NOT_EQUALS),
            new JsonFilterCriteria('metadata', 'email', 'admin', JsonFilterType::STARTS_WITH),
            new JsonFilterCriteria('metadata', 'email', '.com', JsonFilterType::ENDS_WITH),
        ]);

        $this->tableProvider->setFilterCriteria($filterCriteria);

        $this->tableMatchesSnapshot(new Parameters(1, 10, null, $filterCriteria));
    }

    public function testJsonFilterWithNestedArrays(): void
    {
        $this->tableProvider->setFilterableColumns([
            new FilterableColumn('First Contact Phone', 'contacts', FilterableFactory::json('list[0].phones[0]')),
            new FilterableColumn('Second User Name', 'users', FilterableFactory::json('data[1].profile.name')),
            new FilterableColumn('Matrix Value', 'matrix', FilterableFactory::json('values[0][1]')),
        ]);

        $filterCriteria = new FilterCriteria([
            new JsonFilterCriteria('contacts', 'list[0].phones[0]', '+420123456', JsonFilterType::EQUALS),
            new JsonFilterCriteria('users', 'data[1].profile.name', 'Jane', JsonFilterType::CONTAINS),
            new JsonFilterCriteria('matrix', 'values[0][1]', '42', JsonFilterType::EQUALS),
        ]);

        $this->tableProvider->setFilterCriteria($filterCriteria);

        $this->tableMatchesSnapshot(new Parameters(1, 10, null, $filterCriteria));
    }

    public function testJsonFilterWithRootPath(): void
    {
        $this->tableProvider->setFilterableColumns([
            new FilterableColumn('Full JSON Content', 'json_data', FilterableFactory::json()),
            new FilterableColumn('Settings Content', 'settings', FilterableFactory::json()),
        ]);

        $filterCriteria = new FilterCriteria([
            new JsonFilterCriteria('json_data', null, 'search_value', JsonFilterType::CONTAINS),
            new JsonFilterCriteria('settings', '', 'config', JsonFilterType::CONTAINS),
        ]);

        $this->tableProvider->setFilterCriteria($filterCriteria);

        $this->tableMatchesSnapshot(new Parameters(1, 10, null, $filterCriteria));
    }

    public function testProviderHaveJsonFilterCriteria(): void
    {
        $this->tableProvider->setFilterableColumns([
            new FilterableColumn('Email', 'metadata', FilterableFactory::json('email')),
            new FilterableColumn('Status', 'data', FilterableFactory::json('status')),
        ]);

        $filterCriteria = new FilterCriteria([
            new JsonFilterCriteria('metadata', 'email', 'test@example.com', JsonFilterType::CONTAINS),
            new JsonFilterCriteria('data', 'status', 'active', JsonFilterType::EQUALS),
        ]);

        $this->tableProvider->setFilterCriteria($filterCriteria);

        $this->tableMatchesSnapshot(new Parameters(1, 10, null, $filterCriteria));
        $this->assertEquals($filterCriteria, $this->tableProvider->getFilterCriteria());
    }

    public function testCanGetHtmlPartsWithJsonFilters(): void
    {
        $this->tableProvider->setFilterableColumns([
            new FilterableColumn('User Email', 'metadata', FilterableFactory::json('email')),
            new FilterableColumn('User Phone', 'metadata', FilterableFactory::json('phone')),
            new FilterableColumn('Address City', 'data', FilterableFactory::json('address.city')),
            new FilterableColumn('First Tag', 'tags', FilterableFactory::json('items[0]')),
        ]);

        $htmlParts = $this->tableProvider->getHtmlParts(new Parameters(1, 10, null, null));

        $this->assertMatchesHtmlSnapshot($htmlParts['body']);
        $this->assertMatchesHtmlSnapshot($htmlParts['pagination']);
        $this->assertMatchesHtmlSnapshot($htmlParts['head']);
        $this->assertMatchesHtmlSnapshot($htmlParts['options']);
    }

    public function testJsonFilterDisplaysCorrectly(): void
    {
        $this->tableProvider->setFilterableColumns([
            new FilterableColumn('User Email', 'metadata', FilterableFactory::json('email')),
            new FilterableColumn('Deep Path', 'data', FilterableFactory::json('user.profile.settings.theme')),
        ]);

        $filterCriteria = new FilterCriteria([
            new JsonFilterCriteria('metadata', 'email', 'admin@example.com', JsonFilterType::EQUALS),
            new JsonFilterCriteria('data', 'user.profile.settings.theme', 'dark', JsonFilterType::CONTAINS),
        ]);

        $this->tableProvider->setFilterCriteria($filterCriteria);

        $htmlParts = $this->tableProvider->getHtmlParts(new Parameters(1, 10, null, $filterCriteria));

        // Check that the filter values are displayed correctly
        $this->assertStringContainsString('User Email', $htmlParts['options']);
        $this->assertStringContainsString(': equals -', $htmlParts['options']);
        $this->assertStringContainsString('admin@example.com', $htmlParts['options']);
        $this->assertStringContainsString('Deep Path', $htmlParts['options']);
        $this->assertStringContainsString(': contains -', $htmlParts['options']);
        $this->assertStringContainsString('dark', $htmlParts['options']);

        // Check that JSON paths are shown in the filter display
        $this->assertStringContainsString('email', $htmlParts['options']);
        $this->assertStringContainsString('user.profile.settings.theme', $htmlParts['options']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tableProvider = new JsonFilterTableProvider();
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
