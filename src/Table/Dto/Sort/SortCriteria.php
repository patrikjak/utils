<?php

namespace Patrikjak\Utils\Table\Dto\Sort;

final readonly class SortCriteria
{
    public function __construct(
        public string $column,
        public SortOrder $order = SortOrder::ASC,
    ) {
    }

    public function toArray(): array
    {
        return [
            'column' => $this->column,
            'order' => $this->order->value,
        ];
    }
}