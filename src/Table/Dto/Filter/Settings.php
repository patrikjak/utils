<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Dto\Filter;

use Patrikjak\Utils\Common\ValueObjects\Filter\FilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\FilterableColumn;

readonly class Settings
{
    /**
     * @param array<FilterableColumn> $filterableColumns
     */
    public function __construct(public array $filterableColumns, public ?FilterCriteria $criteria)
    {
    }
}
