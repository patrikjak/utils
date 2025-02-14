<?php

namespace Patrikjak\Utils\Table\View\Sort;

use Patrikjak\Utils\Table\Dto\Sort\SortCriteria;

final readonly class SortOption
{
    public function __construct(public SortCriteria $criteria, public string $label)
    {
    }
}