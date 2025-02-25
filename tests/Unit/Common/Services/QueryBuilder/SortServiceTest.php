<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Unit\Common\Services\QueryBuilder;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\DatabaseManager;
use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Common\Dto\Sort\SortCriteria;
use Patrikjak\Utils\Common\Enums\Sort\SortOrder;
use Patrikjak\Utils\Common\Services\QueryBuilder\SortService;

class SortServiceTest extends TestCase
{
    /**
     * @throws BindingResolutionException
     */
    public function testCanApplySort(): void
    {
        $sortService = $this->app->make(SortService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $sortService->applySort($query, new SortCriteria('name', SortOrder::DESC));

        $this->assertStringContainsString('order by "name" desc', $query->toSql());
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplySortWithColumnMask(): void
    {
        $sortService = $this->app->make(SortService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $sortService->applySort($query, new SortCriteria('name', SortOrder::DESC), ['users.name' => 'name']);

        $this->assertStringContainsString('order by "users"."name" desc', $query->toSql());
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanApplySortWithNullValue(): void
    {
        $sortService = $this->app->make(SortService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $sortService->applySort($query, null);

        $this->assertStringNotContainsString('order by', $query->toSql());
    }
}