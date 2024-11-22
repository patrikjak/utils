<?php

namespace Patrikjak\Utils\Tests\Unit\Table\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Common\Interfaces\Paginator;
use Patrikjak\Utils\Table\Interfaces\Pagination\LinkItem;
use Patrikjak\Utils\Table\Services\PaginatorFactory;

class PaginatorFactoryTest extends TestCase
{
    public function testPaginatorFromLengthAwarePaginatorCanBeCreated(): void
    {
        $mockedLengthAwarePaginator = $this->getMockedLengthAwarePaginator();
        assert($mockedLengthAwarePaginator instanceof LengthAwarePaginator);

        $paginator = PaginatorFactory::createFromLengthAwarePaginator($mockedLengthAwarePaginator);

        $this->assertInstanceOf(Paginator::class, $paginator);
    }

    public function testLinkItemsCanBeMappedFromLinkCollection(): void
    {
        $mockedLengthAwarePaginator = $this->getMockedLengthAwarePaginator();
        assert($mockedLengthAwarePaginator instanceof LengthAwarePaginator);

        $paginatorLinks = PaginatorFactory::getLinkItems($mockedLengthAwarePaginator->linkCollection());

        foreach ($paginatorLinks as $link) {
            $this->assertInstanceOf(LinkItem::class, $link);
        }
    }

    private function getMockedLengthAwarePaginator(): MockInterface
    {
        return $this->mock(LengthAwarePaginator::class, function (MockInterface $mock) {
            $mock->shouldReceive('currentPage')->andReturn(1);
            $mock->shouldReceive('perPage')->andReturn(10);
            $mock->shouldReceive('getCollection')->andReturn(new Collection());
            $mock->shouldReceive('path')->andReturn('https://example.com');
            $mock->shouldReceive('lastPage')->andReturn(4);
            $mock->shouldReceive('linkCollection')->andReturn(new Collection([
                ['label' => __('pagination.previous'), 'url' => null, 'active' => false],
                ['label' => '1', 'url' => 'https://example.com/page/1', 'active' => true],
                ['label' => '2', 'url' => 'https://example.com/page/1', 'active' => true],
                ['label' => '3', 'url' => 'https://example.com/page/1', 'active' => true],
                ['label' => '4', 'url' => 'https://example.com/page/1', 'active' => true],
                ['label' => __('pagination.next'), 'url' => 'https://example.com/page/1', 'active' => false],
            ]));
        });
    }
}
