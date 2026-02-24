<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Unit\Common\Services\QueryBuilder;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\DatabaseManager;
use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Common\Dto\Filter\FilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\JsonFilterCriteria;
use Patrikjak\Utils\Common\Enums\Filter\JsonFilterType;
use Patrikjak\Utils\Common\Services\QueryBuilder\FilterService;

class JsonFilterTest extends TestCase
{
    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyJsonFilterWithContains(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('metadata', 'email', 'john@example.com', JsonFilterType::CONTAINS),
        ]));

        $sql = $query->toRawSql();
        $this->assertStringContainsString("JSON_UNQUOTE(JSON_EXTRACT(\"metadata\", '$.email'))", $sql);
        $this->assertStringContainsString("like '%john@example.com%'", $sql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyJsonFilterWithNotContains(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('metadata', 'email', 'spam', JsonFilterType::NOT_CONTAINS),
        ]));

        $sql = $query->toRawSql();
        $this->assertStringContainsString("JSON_UNQUOTE(JSON_EXTRACT(\"metadata\", '$.email'))", $sql);
        $this->assertStringContainsString("not like '%spam%'", $sql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyJsonFilterWithEquals(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('settings', 'theme', 'dark', JsonFilterType::EQUALS),
        ]));

        $sql = $query->toRawSql();
        $this->assertStringContainsString("JSON_UNQUOTE(JSON_EXTRACT(\"settings\", '$.theme'))", $sql);
        $this->assertStringContainsString("= 'dark'", $sql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyJsonFilterWithNotEquals(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('settings', 'theme', 'light', JsonFilterType::NOT_EQUALS),
        ]));

        $sql = $query->toRawSql();
        $this->assertStringContainsString("JSON_UNQUOTE(JSON_EXTRACT(\"settings\", '$.theme'))", $sql);
        $this->assertStringContainsString("!= 'light'", $sql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyJsonFilterWithStartsWith(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('metadata', 'phone', '+420', JsonFilterType::STARTS_WITH),
        ]));

        $sql = $query->toRawSql();
        $this->assertStringContainsString("JSON_UNQUOTE(JSON_EXTRACT(\"metadata\", '$.phone'))", $sql);
        $this->assertStringContainsString("like '+420%'", $sql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyJsonFilterWithEndsWith(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('metadata', 'email', '@example.com', JsonFilterType::ENDS_WITH),
        ]));

        $sql = $query->toRawSql();
        $this->assertStringContainsString("JSON_UNQUOTE(JSON_EXTRACT(\"metadata\", '$.email'))", $sql);
        $this->assertStringContainsString("like '%@example.com'", $sql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyJsonFilterWithNestedPath(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('data', 'user.address.city', 'Prague', JsonFilterType::EQUALS),
        ]));

        $sql = $query->toRawSql();
        $this->assertStringContainsString("JSON_UNQUOTE(JSON_EXTRACT(\"data\", '$.user.address.city'))", $sql);
        $this->assertStringContainsString("= 'Prague'", $sql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyJsonFilterWithRootPath(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('data', null, 'test', JsonFilterType::CONTAINS),
        ]));

        $sql = $query->toRawSql();
        $this->assertStringContainsString("JSON_UNQUOTE(JSON_EXTRACT(\"data\", '$'))", $sql);
        $this->assertStringContainsString("like '%test%'", $sql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyJsonFilterWithEmptyPath(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('data', '', 'value', JsonFilterType::EQUALS),
        ]));

        $sql = $query->toRawSql();
        $this->assertStringContainsString("JSON_UNQUOTE(JSON_EXTRACT(\"data\", '$'))", $sql);
        $this->assertStringContainsString("= 'value'", $sql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyJsonFilterWithPathStartingWithDollar(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('data', '$.name', 'John', JsonFilterType::EQUALS),
        ]));

        $sql = $query->toRawSql();
        $this->assertStringContainsString("JSON_UNQUOTE(JSON_EXTRACT(\"data\", '$.name'))", $sql);
        $this->assertStringContainsString("= 'John'", $sql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyMultipleJsonFiltersOnSameColumn(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('metadata', 'email', 'john', JsonFilterType::CONTAINS),
            new JsonFilterCriteria('metadata', 'email', 'spam', JsonFilterType::NOT_CONTAINS),
        ]));

        $sql = $query->toRawSql();
        $this->assertStringContainsString("JSON_UNQUOTE(JSON_EXTRACT(\"metadata\", '$.email')) like '%john%'", $sql);
        $this->assertStringContainsString(
            "or JSON_UNQUOTE(JSON_EXTRACT(\"metadata\", '$.email')) not like '%spam%'",
            $sql,
        );
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyJsonFilterWithColumnMask(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter(
            $query,
            new FilterCriteria([
                new JsonFilterCriteria('user_metadata', 'email', 'test', JsonFilterType::CONTAINS),
            ]),
            ['users.metadata' => 'user_metadata']
        );

        $sql = $query->toRawSql();
        $this->assertStringContainsString("JSON_UNQUOTE(JSON_EXTRACT(\"users\".\"metadata\", '$.email'))", $sql);
        $this->assertStringContainsString("like '%test%'", $sql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testJsonFilterConditionLogic(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('metadata', 'email', 'john', JsonFilterType::CONTAINS),
            new JsonFilterCriteria('metadata', 'email', 'doe', JsonFilterType::CONTAINS),
            new JsonFilterCriteria('settings', 'theme', 'dark', JsonFilterType::EQUALS),
        ]));

        $sql = $query->toRawSql();

        // First group (metadata column filters with OR)
        $this->assertStringContainsString(
            "where (JSON_UNQUOTE(JSON_EXTRACT(\"metadata\", '$.email')) like '%john%'",
            $sql,
        );
        $this->assertStringContainsString("or JSON_UNQUOTE(JSON_EXTRACT(\"metadata\", '$.email')) like '%doe%')", $sql);

        // Second group (settings column filter)
        $this->assertStringContainsString("and (JSON_UNQUOTE(JSON_EXTRACT(\"settings\", '$.theme')) = 'dark')", $sql);
    }
}
