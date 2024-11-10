<?php

namespace Patrikjak\Utils\Tests\Integration\Table;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Table\Dto\Pagination\LinkItem;
use Patrikjak\Utils\Table\Dto\Pagination\Settings;
use Patrikjak\Utils\Table\View\Pagination\Paginator;
use Spatie\Snapshots\MatchesSnapshots;

class PaginationTest extends TestCase
{
    use MatchesSnapshots;

    private const string PATH = 'https://example.com/paginator';

    public function testPaginatorWithFourPagesAndFirstPageActiveIsRendered(): void
    {
        $this->assertPaginatorMatchesSnapshot(new Collection([
            new LinkItem(__('pagination.previous'), null),
            new LinkItem('1', sprintf('%s/1', self::PATH), true),
            new LinkItem('2', sprintf('%s/2', self::PATH)),
            new LinkItem('3', sprintf('%s/3', self::PATH)),
            new LinkItem('4', sprintf('%s/4', self::PATH)),
            new LinkItem(__('pagination.next'), sprintf('%s/2', self::PATH)),
        ]));
    }

    public function testPaginatorWithFourPagesAndLastActive(): void
    {
        $this->assertPaginatorMatchesSnapshot(new Collection([
            new LinkItem(__('pagination.previous'), sprintf('%s/3', self::PATH)),
            new LinkItem('1', sprintf('%s/1', self::PATH)),
            new LinkItem('2', sprintf('%s/2', self::PATH)),
            new LinkItem('3', sprintf('%s/3', self::PATH)),
            new LinkItem('4', sprintf('%s/4', self::PATH), true),
            new LinkItem(__('pagination.next'), null),
        ]), 4);
    }

    public function testPaginatorWithNinePages(): void
    {
        $this->assertPaginatorMatchesSnapshot(new Collection([
            new LinkItem(__('pagination.previous'), sprintf('%s/3', self::PATH)),
            new LinkItem('1', sprintf('%s/1', self::PATH)),
            new LinkItem('2', sprintf('%s/2', self::PATH)),
            new LinkItem('3', sprintf('%s/3', self::PATH)),
            new LinkItem('4', sprintf('%s/4', self::PATH), true),
            new LinkItem('5', sprintf('%s/5', self::PATH)),
            new LinkItem('6', sprintf('%s/6', self::PATH)),
            new LinkItem('7', sprintf('%s/7', self::PATH)),
            new LinkItem('8', sprintf('%s/8', self::PATH)),
            new LinkItem('9', sprintf('%s/9', self::PATH)),
            new LinkItem(__('pagination.next'), sprintf('%s/5', self::PATH)),
        ]), 4);
    }

    public function testPaginatorWithTwelvePages(): void
    {
        $this->assertPaginatorMatchesSnapshot(new Collection([
            new LinkItem(__('pagination.previous'), sprintf('%s/3', self::PATH)),
            new LinkItem('1', sprintf('%s/1', self::PATH)),
            new LinkItem('2', sprintf('%s/2', self::PATH)),
            new LinkItem('3', sprintf('%s/3', self::PATH)),
            new LinkItem('4', sprintf('%s/4', self::PATH), true),
            new LinkItem('5', sprintf('%s/5', self::PATH)),
            new LinkItem('6', sprintf('%s/6', self::PATH)),
            new LinkItem('7', sprintf('%s/7', self::PATH)),
            new LinkItem('8', sprintf('%s/8', self::PATH)),
            new LinkItem('9', sprintf('%s/9', self::PATH)),
            new LinkItem('10', sprintf('%s/10', self::PATH)),
            new LinkItem('11', sprintf('%s/11', self::PATH)),
            new LinkItem('12', sprintf('%s/12', self::PATH)),
            new LinkItem(__('pagination.next'), sprintf('%s/5', self::PATH)),
        ]), 4);
    }

    public function testPaginatorWithTwelvePagesAndFifthPageActive(): void
    {
        $this->assertPaginatorMatchesSnapshot(new Collection([
            new LinkItem(__('pagination.previous'), sprintf('%s/4', self::PATH)),
            new LinkItem('1', sprintf('%s/1', self::PATH)),
            new LinkItem('2', sprintf('%s/2', self::PATH)),
            new LinkItem('3', sprintf('%s/3', self::PATH)),
            new LinkItem('4', sprintf('%s/4', self::PATH)),
            new LinkItem('5', sprintf('%s/5', self::PATH), true),
            new LinkItem('6', sprintf('%s/6', self::PATH)),
            new LinkItem('7', sprintf('%s/7', self::PATH)),
            new LinkItem('8', sprintf('%s/8', self::PATH)),
            new LinkItem('9', sprintf('%s/9', self::PATH)),
            new LinkItem('10', sprintf('%s/10', self::PATH)),
            new LinkItem('11', sprintf('%s/11', self::PATH)),
            new LinkItem('12', sprintf('%s/12', self::PATH)),
            new LinkItem(__('pagination.next'), sprintf('%s/6', self::PATH)),
        ]), 5);
    }

    private function assertPaginatorMatchesSnapshot(Collection $links, int $activePage = 1): void
    {
        $paginator = Blade::renderComponent(new Paginator($this->getPaginatorSettings($links, $activePage)));

        $this->assertMatchesHtmlSnapshot($paginator);
    }

    private function getPaginatorSettings(Collection $links, int $activePage = 1): Settings
    {
        return new Settings(
            page: $activePage,
            pageSize: 10,
            pageSizeOptions: [10 => 10, 20 => 20, 50 => 50, 100 => 100],
            path: self::PATH,
            links: $links,
            lastPage: $links->count() - 2,
        );
    }
}
