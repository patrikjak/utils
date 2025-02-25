<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Services\QueryBuilder;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginatorService
{
    public function paginate(Builder $query, int $page, int $perPage, ?string $path): LengthAwarePaginator
    {
        $query = $query->paginate($perPage, page: $page);

        assert($query instanceof LengthAwarePaginator);

        if ($path !== null) {
            $query->withPath($path);
        }

        return $query;
    }
}