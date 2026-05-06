<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Dto\Search;

readonly class Settings
{
    /**
     * @param array<string> $searchableColumns
     */
    public function __construct(public array $searchableColumns, public ?string $searchQuery)
    {
    }
}
