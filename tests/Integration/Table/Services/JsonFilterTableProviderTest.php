<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Table\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Table\Builder\Filter as FilterFactory;
use Patrikjak\Utils\Table\Builder\TableBuilder;
use Patrikjak\Utils\Table\Contracts\Filter\NeedsDatabaseColumn;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Enums\Filter\JsonFilterType;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\FilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\JsonFilterCriteria;
use Patrikjak\Utils\Tests\Integration\Table\Services\Implementations\JsonFilterTableProvider;
use Patrikjak\Utils\Tests\Integration\Table\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

final class JsonFilterTableProviderTest extends TestCase
{
    use MatchesSnapshots;

    private JsonFilterTableProvider $tableProvider;

    /**
     * @throws BindingResolutionException
     */
    public function testTableWithJsonFilterableColumns(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder
                ->filter('metadata', FilterFactory::json('email'))
                ->filter('data', FilterFactory::json('address.city'))
                ->filter('tags', FilterFactory::json('items[0]'))
                ->filter('settings', FilterFactory::json('theme'))
                ->filter('preferences', FilterFactory::json());
        });

        $this->tableMatchesSnapshot();
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableWithJsonFilterCriteria(): void
    {
        $filterCriteria = new FilterCriteria([
            new JsonFilterCriteria('metadata', 'email', 'john@example.com', JsonFilterType::CONTAINS),
            new JsonFilterCriteria('data', 'address.city', 'Prague', JsonFilterType::EQUALS),
            new JsonFilterCriteria('tags', 'items[0]', 'tech', JsonFilterType::CONTAINS),
            new JsonFilterCriteria('settings', 'theme', 'dark', JsonFilterType::EQUALS),
        ]);

        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder
                ->filter('metadata', FilterFactory::json('email'))
                ->filter('data', FilterFactory::json('address.city'))
                ->filter('tags', FilterFactory::json('items[0]'))
                ->filter('settings', FilterFactory::json('theme'));
        });

        $this->tableMatchesSnapshot(new Parameters(1, 10, null, $filterCriteria));
    }

    /**
     * @throws BindingResolutionException
     */
    public function testJsonFilterWithDifferentOperators(): void
    {
        $filterCriteria = new FilterCriteria([
            new JsonFilterCriteria('metadata', 'email', 'john', JsonFilterType::CONTAINS),
            new JsonFilterCriteria('metadata', 'email', 'spam', JsonFilterType::NOT_CONTAINS),
            new JsonFilterCriteria('data', 'status', 'active', JsonFilterType::EQUALS),
            new JsonFilterCriteria('data', 'status', 'inactive', JsonFilterType::NOT_EQUALS),
            new JsonFilterCriteria('metadata', 'email', 'admin', JsonFilterType::STARTS_WITH),
            new JsonFilterCriteria('metadata', 'email', '.com', JsonFilterType::ENDS_WITH),
        ]);

        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder
                ->filter('metadata', FilterFactory::json('email'))
                ->filter('data', FilterFactory::json('status'));
        });

        $this->tableMatchesSnapshot(new Parameters(1, 10, null, $filterCriteria));
    }

    /**
     * @throws BindingResolutionException
     */
    public function testJsonFilterWithNestedArrays(): void
    {
        $filterCriteria = new FilterCriteria([
            new JsonFilterCriteria('contacts', 'list[0].phones[0]', '+420123456', JsonFilterType::EQUALS),
            new JsonFilterCriteria('users', 'data[1].profile.name', 'Jane', JsonFilterType::CONTAINS),
            new JsonFilterCriteria('matrix', 'values[0][1]', '42', JsonFilterType::EQUALS),
        ]);

        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder
                ->filter('contacts', FilterFactory::json('list[0].phones[0]'))
                ->filter('users', FilterFactory::json('data[1].profile.name'))
                ->filter('matrix', FilterFactory::json('values[0][1]'));
        });

        $this->tableMatchesSnapshot(new Parameters(1, 10, null, $filterCriteria));
    }

    /**
     * @throws BindingResolutionException
     */
    public function testJsonFilterWithRootPath(): void
    {
        $filterCriteria = new FilterCriteria([
            new JsonFilterCriteria('json_data', null, 'search_value', JsonFilterType::CONTAINS),
            new JsonFilterCriteria('settings', '', 'config', JsonFilterType::CONTAINS),
        ]);

        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder
                ->filter('json_data', FilterFactory::json())
                ->filter('settings', FilterFactory::json());
        });

        $this->tableMatchesSnapshot(new Parameters(1, 10, null, $filterCriteria));
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanGetHtmlPartsWithJsonFilters(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder
                ->filter('metadata', FilterFactory::json('email'))
                ->filter('data', FilterFactory::json('address.city'))
                ->filter('tags', FilterFactory::json('items[0]'))
                ->filter('contacts', FilterFactory::json('list[0].phones[0]'));
        });

        $htmlParts = $this->tableProvider->getHtmlParts(new Parameters(1, 10, null, null));

        $this->assertMatchesHtmlSnapshot($htmlParts['body']);
        $this->assertMatchesHtmlSnapshot($htmlParts['head']);
        $this->assertMatchesHtmlSnapshot($htmlParts['options']);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testJsonFilterDisplaysCorrectly(): void
    {
        $filterCriteria = new FilterCriteria([
            new JsonFilterCriteria('metadata', 'email', 'admin@example.com', JsonFilterType::EQUALS),
            new JsonFilterCriteria('data', 'user.profile.settings.theme', 'dark', JsonFilterType::CONTAINS),
        ]);

        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder
                ->filter('metadata', FilterFactory::json('email'))
                ->filter('data', FilterFactory::json('user.profile.settings.theme'));
        });

        $htmlParts = $this->tableProvider->getHtmlParts(new Parameters(1, 10, null, $filterCriteria));

        $this->assertStringContainsString('Metadata', $htmlParts['options']);
        $this->assertStringContainsString(': equals -', $htmlParts['options']);
        $this->assertStringContainsString('admin@example.com', $htmlParts['options']);
        $this->assertStringContainsString('Data', $htmlParts['options']);
        $this->assertStringContainsString(': contains -', $htmlParts['options']);
        $this->assertStringContainsString('dark', $htmlParts['options']);

        $this->assertStringContainsString('email', $htmlParts['options']);
        $this->assertStringContainsString('user.profile.settings.theme', $htmlParts['options']);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testFilterColumnMapPreEnrichesParametersBeforeBuild(): void
    {
        $filterCriteria = new FilterCriteria([
            new JsonFilterCriteria('metadata', 'email', 'john@example.com', JsonFilterType::CONTAINS),
        ]);

        $this->tableProvider->setColumnMap(['metadata' => 'users.metadata']);
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder->filter('metadata', FilterFactory::json('email'));
        });

        $table = $this->tableProvider->getTable(new Parameters(1, 10, null, $filterCriteria));

        $enrichedFilters = $table->parameters?->filterCriteria?->filters ?? [];
        $this->assertNotEmpty($enrichedFilters);

        $first = $enrichedFilters[0];
        $this->assertInstanceOf(NeedsDatabaseColumn::class, $first);
        $this->assertSame('users.metadata', $first->getDatabaseColumn());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tableProvider = new JsonFilterTableProvider();
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
