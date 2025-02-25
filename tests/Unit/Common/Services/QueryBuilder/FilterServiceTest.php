<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Unit\Common\Services\QueryBuilder;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\DatabaseManager;
use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Common\Dto\Filter\DateFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\FilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\NumberFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\SelectFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\TextFilterCriteria;
use Patrikjak\Utils\Common\Enums\Filter\TextFilterType;
use Patrikjak\Utils\Common\Services\QueryBuilder\FilterService;

class FilterServiceTest extends TestCase
{
    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyFilter(): void
    {
        $sortService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $sortService->applyFilter($query, new FilterCriteria([
            new TextFilterCriteria('name', 'John', TextFilterType::CONTAINS),
            new TextFilterCriteria('name', 'Lucas', TextFilterType::NOT_CONTAINS),
            new TextFilterCriteria('name', 'Filip', TextFilterType::EQUALS),
            new TextFilterCriteria('name', 'Patrik', TextFilterType::NOT_EQUALS),
            new TextFilterCriteria('name', 'Jakub', TextFilterType::STARTS_WITH),
            new TextFilterCriteria('name', 'Kuba', TextFilterType::ENDS_WITH),
            new SelectFilterCriteria('email', 'email@example.com'),
            new DateFilterCriteria(
                'created_at',
                CarbonImmutable::make('2021-01-01'),
                CarbonImmutable::make('2021-01-31'),
            ),
            new DateFilterCriteria('updated_at', null, CarbonImmutable::make('2021-01-31')),
            new NumberFilterCriteria('random_number', -5, 5),
            new NumberFilterCriteria('random_number', null, 5),
            new NumberFilterCriteria('random_number', -5, null),
        ]));

        $this->assertStringContainsString("\"name\" like '%John%'", $query->toRawSql());
        $this->assertStringContainsString("\"name\" not like '%Lucas%'", $query->toRawSql());
        $this->assertStringContainsString("\"name\" = 'Filip'", $query->toRawSql());
        $this->assertStringContainsString("\"name\" != 'Patrik'", $query->toRawSql());
        $this->assertStringContainsString("\"name\" like 'Jakub%'", $query->toRawSql());
        $this->assertStringContainsString("\"name\" like '%Kuba'", $query->toRawSql());
        $this->assertStringContainsString("\"email\" = 'email@example.com'", $query->toRawSql());
        $this->assertStringContainsString(
            "\"created_at\" between '2021-01-01 00:00:00' and '2021-01-31 00:00:00'",
            $query->toRawSql(),
        );
        $this->assertStringContainsString("\"updated_at\" <= '2021-01-31 00:00:00'", $query->toRawSql());
        $this->assertStringContainsString("\"random_number\" between -5 and 5", $query->toRawSql());
        $this->assertStringContainsString("\"random_number\" <= 5", $query->toRawSql());
        $this->assertStringContainsString("\"random_number\" >= -5", $query->toRawSql());
    }

    /**
     * @throws BindingResolutionException
     */
    public function testFilterConditionLogic(): void
    {
        $sortService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $sortService->applyFilter($query, new FilterCriteria([
            new TextFilterCriteria('name', 'John', TextFilterType::CONTAINS),
            new TextFilterCriteria('name', 'Lucas', TextFilterType::NOT_CONTAINS),
            new SelectFilterCriteria('email', 'email@example.com'),
            new DateFilterCriteria(
                'created_at',
                CarbonImmutable::make('2021-01-01'),
                CarbonImmutable::make('2021-01-31'),
            ),
            new DateFilterCriteria('updated_at', null, CarbonImmutable::make('2021-01-31')),
            new NumberFilterCriteria('random_number', -5, 5),
            new NumberFilterCriteria('random_number', null, 5),
        ]));

        $this->assertStringContainsString("where (\"name\" like '%John%'", $query->toRawSql());
        $this->assertStringContainsString("or \"name\" not like '%Lucas%') and", $query->toRawSql());
        $this->assertStringContainsString("and (\"email\" = 'email@example.com')", $query->toRawSql());
        $this->assertStringContainsString(
            "and (\"created_at\" between '2021-01-01 00:00:00' and '2021-01-31 00:00:00')",
            $query->toRawSql(),
        );
        $this->assertStringContainsString("and (\"updated_at\" <= '2021-01-31 00:00:00')", $query->toRawSql());
        $this->assertStringContainsString("and (\"random_number\" between -5 and 5 or", $query->toRawSql());
        $this->assertStringContainsString("or \"random_number\" <= 5)", $query->toRawSql());
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyFilterWithColumnMask(): void
    {
        $sortService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $sortService->applyFilter(
            $query,
            new FilterCriteria([
                new TextFilterCriteria('name', 'John', TextFilterType::CONTAINS),
            ]),
            ['users.name' => 'name']);

        $this->assertStringContainsString("\"name\" like '%John%'", $query->toRawSql());
    }

    /**
     * @throws BindingResolutionException
     */
    public function testDoNotApplyRangeFilterWithEmptyValues(): void
    {
        $sortService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $sortService->applyFilter($query, new FilterCriteria([
            new DateFilterCriteria('created_at', null, null),
        ]));

        $this->assertStringNotContainsString('where', $query->toSql());
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyFilterWithNullValue(): void
    {
        $sortService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $sortService->applyFilter($query, null);

        $this->assertStringNotContainsString('where', $query->toSql());
    }
}