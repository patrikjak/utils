<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Common\Services\QueryBuilder;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\DatabaseManager;
use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Common\Services\QueryBuilder\FilterService;

class FilterServiceSearchTest extends TestCase
{
    /**
     * @throws BindingResolutionException
     */
    public function testCanApplySearch(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applySearch($query, 'john', ['name', 'email']);

        $this->assertStringContainsString("\"name\" like '%john%'", $query->toRawSql());
        $this->assertStringContainsString("\"email\" like '%john%'", $query->toRawSql());
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSearchUsesOrConditionsBetweenColumns(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applySearch($query, 'test', ['name', 'email', 'role']);

        $rawSql = $query->toRawSql();
        $this->assertStringContainsString('where (', $rawSql);
        $this->assertStringContainsString('"name" like', $rawSql);
        $this->assertStringContainsString('"email" like', $rawSql);
        $this->assertStringContainsString('"role" like', $rawSql);
        $this->assertStringContainsString('or', $rawSql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSearchDoesNothingWithNullQuery(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applySearch($query, null, ['name', 'email']);

        $this->assertStringNotContainsString('where', $query->toSql());
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSearchDoesNothingWithEmptyQuery(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applySearch($query, '', ['name', 'email']);

        $this->assertStringNotContainsString('where', $query->toSql());
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSearchDoesNothingWithEmptyColumns(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applySearch($query, 'john', []);

        $this->assertStringNotContainsString('where', $query->toSql());
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSearchEscapesSpecialCharacters(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applySearch($query, 'jo%hn', ['name']);

        // The % character should be escaped, not treated as a wildcard
        $rawSql = $query->toRawSql();
        $this->assertStringNotContainsString("like '%jo%hn%'", $rawSql);
        $this->assertStringContainsString('jo', $rawSql);
        $this->assertStringContainsString('hn', $rawSql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSearchAppliesColumnMask(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applySearch($query, 'john', ['name'], ['name' => 'users.name']);

        $this->assertStringContainsString('"users"."name" like', $query->toRawSql());
    }
}
