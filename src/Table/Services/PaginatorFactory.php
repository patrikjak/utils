<?php

namespace Patrikjak\Utils\Table\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Patrikjak\Utils\Table\Dto\Pagination\LinkItem;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator;

final readonly class PaginatorFactory
{
    public static function createFromLengthAwarePaginator(LengthAwarePaginator $lengthAwarePaginator): Paginator
    {
        return new Paginator(
            $lengthAwarePaginator->currentPage(),
            $lengthAwarePaginator->perPage(),
            $lengthAwarePaginator->getCollection(),
            $lengthAwarePaginator->path(),
            $lengthAwarePaginator->lastPage(),
            self::getLinkItems($lengthAwarePaginator->linkCollection()),
        );
    }

    /** @return Collection<LinkItem> */
    public static function getLinkItems(Collection $links): Collection
    {
        return $links->map(function ($link) {
            return new LinkItem(
                label: $link['label'],
                url: $link['url'],
                active: $link['active'],
            );
        });
    }
}