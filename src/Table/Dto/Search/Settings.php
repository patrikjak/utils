<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Dto\Search;

use Illuminate\Support\Collection;

readonly class Settings
{
    /**
     * @param Collection<int, string> $searchableColumns
     */
    public function __construct(public Collection $searchableColumns, public ?string $searchQuery)
    {
    }
}
