<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Services;

use Patrikjak\Utils\Common\Dto\Filter\FilterCriteria;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\FilterableColumn;

interface Filterable
{
    /**
     * @return array<FilterableColumn>
     */
    public function getFilterableColumns(): array;

    public function getFilterCriteria(): ?FilterCriteria;
}