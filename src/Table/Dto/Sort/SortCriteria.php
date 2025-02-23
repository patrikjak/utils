<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Sort;

use Patrikjak\Utils\Table\Enums\Sort\SortOrder;

final readonly class SortCriteria
{
    public function __construct(
        public string $column,
        public SortOrder $order = SortOrder::ASC,
    ) {
    }

    /**
     * @return array{column: string, order: string}
     */
    public function toArray(): array
    {
        return [
            'column' => $this->column,
            'order' => $this->order->value,
        ];
    }
}