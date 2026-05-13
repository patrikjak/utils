<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\View\Filter;

use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;

readonly class FilterOption
{
    public function __construct(
        public string $label,
        public AbstractFilterCriteria $criteria,
        public string $chipView,
    ) {
    }
}
