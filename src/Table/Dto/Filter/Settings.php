<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Filter;

use Patrikjak\Utils\Common\Dto\Filter\FilterCriteria;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\FilterableColumn;

final readonly class Settings
{
    /**
     * @param array<FilterableColumn> $filterableColumns
     */
    public function __construct(public array $filterableColumns, public ?FilterCriteria $criteria)
    {
    }
}