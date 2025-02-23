<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Filter;

use Patrikjak\Utils\Common\Dto\Filter\AbstractFilterCriteria;

final readonly class FilterOption
{
    public function __construct(public string $label, public AbstractFilterCriteria $criteria)
    {
    }
}