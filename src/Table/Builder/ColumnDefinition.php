<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Builder;

use Closure;

final readonly class ColumnDefinition
{
    public function __construct(
        public string $key,
        public string $label,
        public Closure $render,
        public ?string $resolvedDatabaseColumn = null,
        public bool $hidden = false,
    ) {
    }
}
