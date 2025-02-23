<?php

namespace Patrikjak\Utils\Table\View\Filter;

use Patrikjak\Utils\Table\Dto\Filter\Criteria\BaseFilterCriteria;

final readonly class FilterOption
{
    public function __construct(public string $label, public BaseFilterCriteria $criteria)
    {
    }
}