<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Services\QueryBuilder\Filters;

abstract class AbstractFilter
{
    /**
     * @param array<string, string> $columnsMask
     */
    public function getRealColumn(string $column, array $columnsMask): string
    {
        if ($columnsMask !== [] && array_key_exists($column, $columnsMask)) {
            return $columnsMask[$column];
        }

        return $column;
    }
}
