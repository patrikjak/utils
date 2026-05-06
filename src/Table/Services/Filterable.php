<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Services;

use Patrikjak\Utils\Common\ValueObjects\Filter\FilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\FilterableColumn;

interface Filterable
{
    /**
     * @return array<FilterableColumn>
     */
    public function getFilterableColumns(): array;

    public function getFilterCriteria(): ?FilterCriteria;
}
