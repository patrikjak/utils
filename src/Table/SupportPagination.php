<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table;

use Patrikjak\Utils\Table\Pagination\PaginationSettings;
use Patrikjak\Utils\Table\Parameters\TableParameters;

interface SupportPagination
{
    /**
     * @return array<string, string>
     */
    public function getHtmlParts(TableParameters $parameters): array;

    public function getPaginationSettings(): PaginationSettings;
}