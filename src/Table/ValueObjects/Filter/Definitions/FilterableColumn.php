<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Definitions;

use Patrikjak\Utils\Table\Contracts\Filter\FilterDefinition;

readonly class FilterableColumn
{
    public function __construct(
        public string $label,
        public string $column,
        public FilterDefinition $filterDefinition,
    ) {
    }
}
