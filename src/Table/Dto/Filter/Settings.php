<?php

namespace Patrikjak\Utils\Table\Dto\Filter;

use Patrikjak\Utils\Table\Dto\Filter\Definitions\FilterableColumn;

final readonly class Settings
{
    /**
     * @param array<FilterableColumn> $filterableColumns
     */
    public function __construct(public array $filterableColumns)
    {
    }
}