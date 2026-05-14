<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Sort;

use Patrikjak\Utils\Table\Enums\Sort\SortOrder;

readonly class SortCriteria
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
