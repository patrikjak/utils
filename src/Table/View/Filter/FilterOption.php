<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Filter;

use Patrikjak\Utils\Common\Dto\Filter\BaseFilterCriteria;

final readonly class FilterOption
{
    public function __construct(public string $label, public BaseFilterCriteria $criteria)
    {
    }
}