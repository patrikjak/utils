<?php

namespace Patrikjak\Utils\Table\Dto\Sort;

final readonly class SortCriteria
{
    public function __construct(
        public string $column,
        public SortOrder $order = SortOrder::ASC,
    ) {
    }
}