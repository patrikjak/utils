<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Table\Services;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Table\Builder\Filter as FilterFactory;
use Patrikjak\Utils\Table\Builder\TableBuilder;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Enums\Filter\TextFilterType;
use Patrikjak\Utils\Table\Enums\Sort\SortOrder;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\DateFilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\FilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\SelectFilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\TextFilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Sort\SortCriteria;
use Patrikjak\Utils\Tests\Integration\Table\Services\Implementations\SortAndFilterTableProvider;
use Patrikjak\Utils\Tests\Integration\Table\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

final class SortAndFilterTableProviderTest extends TestCase
{
    use MatchesSnapshots;

    private SortAndFilterTableProvider $tableProvider;

    public function testTableWithSortableColumns(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder->sort('id', 'name', 'email', 'created_at', 'updated_at');
        });

        $this->tableMatchesSnapshot();
    }

    public function testTableWithSortCriteria(): void
    {
        $sortCriteria = new SortCriteria('name', SortOrder::DESC);

        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder->sort('id', 'name', 'email', 'created_at', 'updated_at');
        });

        $this->tableMatchesSnapshot(new Parameters(1, 10, $sortCriteria, null));
    }

    public function testTableWithFilterableColumns(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder
                ->filter('id')
                ->filter('name')
                ->filter('email', FilterFactory::select('http://example.com/data'))
                ->filter('created_at', FilterFactory::date())
                ->filter('updated_at', FilterFactory::date());
        });

        $this->tableMatchesSnapshot();
    }

    public function testTableWithFilterCriteria(): void
    {
        $filterCriteria = new FilterCriteria([
            new TextFilterCriteria('name', 'John', TextFilterType::CONTAINS),
            new SelectFilterCriteria('email', 'example@email.com'),
            new DateFilterCriteria('created_at', CarbonImmutable::make('2024-12-01'), null),
            new DateFilterCriteria('updated_at', null, null),
        ]);

        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder
                ->filter('id')
                ->filter('name')
                ->filter('email', FilterFactory::select('http://example.com/data'))
                ->filter('created_at', FilterFactory::date())
                ->filter('updated_at', FilterFactory::date());
        });

        $this->tableMatchesSnapshot(new Parameters(1, 10, null, $filterCriteria));
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanGetHtmlParts(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder
                ->sort('id', 'name', 'email', 'created_at', 'updated_at')
                ->filter('id')
                ->filter('name')
                ->filter('email', FilterFactory::select('http://example.com/data'))
                ->filter('created_at', FilterFactory::date())
                ->filter('updated_at', FilterFactory::date());
        });

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
