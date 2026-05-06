<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Sort;

final readonly class SortableColumn
{
    public function __construct(public string $label, public string $column)
    {
    }
}
