<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Contracts\Filter;

interface NeedsDatabaseColumn
{
    public function getDatabaseColumn(): ?string;

    public function withDatabaseColumn(string $databaseColumn): static;
}
