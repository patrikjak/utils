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

class JsonFilterIndexedTest extends TestCase
{
    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyJsonFilterWithArrayIndex(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('data', 'items[0]', 'first', JsonFilterType::EQUALS),
        ]));

        $sql = $query->toRawSql();
        $this->assertStringContainsString("JSON_UNQUOTE(JSON_EXTRACT(data, '$.items[0]'))", $sql);
        $this->assertStringContainsString("= 'first'", $sql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyJsonFilterWithNestedArrayIndex(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('data', 'users[0].name', 'John', JsonFilterType::EQUALS),
        ]));

        $sql = $query->toRawSql();
        $this->assertStringContainsString("JSON_UNQUOTE(JSON_EXTRACT(data, '$.users[0].name'))", $sql);
        $this->assertStringContainsString("= 'John'", $sql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyJsonFilterWithMultipleArrayIndexes(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('data', 'matrix[0][1]', 'value', JsonFilterType::CONTAINS),
        ]));

        $sql = $query->toRawSql();
        $this->assertStringContainsString("JSON_UNQUOTE(JSON_EXTRACT(data, '$.matrix[0][1]'))", $sql);
        $this->assertStringContainsString("like '%value%'", $sql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplyJsonFilterWithPathStartingWithDollarAndIndex(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('data', '$.items[2].name', 'Test', JsonFilterType::EQUALS),
        ]));

        $sql = $query->toRawSql();
        $this->assertStringContainsString("JSON_UNQUOTE(JSON_EXTRACT(data, '$.items[2].name'))", $sql);
        $this->assertStringContainsString("= 'Test'", $sql);
    }
}
