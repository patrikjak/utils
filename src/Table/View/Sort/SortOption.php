<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\View\Sort;

use Patrikjak\Utils\Table\ValueObjects\Sort\SortCriteria;

readonly class SortOption
{
    public function __construct(public SortCriteria $criteria, public string $label)
    {
    }
}
