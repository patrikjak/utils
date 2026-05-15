<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Common\Services\QueryBuilder;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\DatabaseManager;
use Patrikjak\Utils\Common\Services\QueryBuilder\FilterService;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\FilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\SearchFilterCriteria;
use Patrikjak\Utils\Tests\Unit\TestCase;

final class TextFilterSearchTest extends TestCase
{
    /**
     * @throws BindingResolutionException
     */
    public function testSearchFansOutAcrossSearchableColumns(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new SearchFilterCriteria('john', ['name', 'email']),
        ]));

        $rawSql = $query->toRawSql();
        $this->assertStringContainsString('"name" like \'%john%\'', $rawSql);
        $this->assertStringContainsString('"email" like \'%john%\'', $rawSql);
        $this->assertStringContainsString('or', $rawSql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSearchColumnsAreWrappedInWhereGroup(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new SearchFilterCriteria('test', ['name', 'email', 'role']),
        ]));

        $rawSql = $query->toRawSql();
        $this->assertStringContainsString('where (', $rawSql);
        $this->assertStringContainsString('"name" like', $rawSql);
        $this->assertStringContainsString('"email" like', $rawSql);
        $this->assertStringContainsString('"role" like', $rawSql);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSearchDoesNothingWithEmptyValue(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new SearchFilterCriteria('', ['name', 'email']),
        ]));

        $this->assertStringNotContainsString('where', $query->toSql());
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSearchDoesNothingWithEmptyColumns(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new SearchFilterCriteria('john', []),
        ]));

        $this->assertStringNotContainsString('where', $query->toSql());
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSearchEscapesSpecialCharacters(): void
    {
        $filterService = $this->app->make(FilterService::class);
        $query = $this->app->make(DatabaseManager::class)->table('users')->select();

        $filterService->applyFilter($query, new FilterCriteria([
            new SearchFilterCriteria('jo%hn', ['name']),
        ]));

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

        $filterService->applyFilter(
            $query,
            new FilterCriteria([new SearchFilterCriteria('john', ['name'])]),
            ['name' => 'users.name'],
        );

        $this->assertStringContainsString('"users"."name" like', $query->toRawSql());
    }
}
