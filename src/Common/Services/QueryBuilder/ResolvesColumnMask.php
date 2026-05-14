<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Services\QueryBuilder;

trait ResolvesColumnMask
{
    /**
     * @param array<string, string> $columnsMask [displayColumn => realDatabaseColumn]
     */
    protected function resolveColumn(string $column, array $columnsMask): string
    {
        return $columnsMask[$column] ?? $column;
    }
}
