<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Unit\Common\Services\QueryBuilder;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Common\Services\QueryBuilder\PaginatorService;

class PaginatorServiceTest extends TestCase
{
    /**
     * @throws BindingResolutionException
     */
    public function testPaginate(): void
    {
        $builder = $this->partialMock(Builder::class, static function (MockInterface $mock): void {
            $mock->shouldReceive('get')->once()->andReturn([]);
            $mock->shouldReceive('getCountForPagination')->once()->andReturn(10);
        });

        $paginatorService = $this->app->make(PaginatorService::class);

        assert($builder instanceof Builder);
        $paginator = $paginatorService->paginate($builder, 1, 10, '/path');

        $this->assertEquals(1, $paginator->currentPage());
        $this->assertEquals(10, $paginator->perPage());
        $this->assertEquals('/path', $paginator->path());
        $this->assertInstanceOf(LengthAwarePaginator::class, $paginator);
    }
}