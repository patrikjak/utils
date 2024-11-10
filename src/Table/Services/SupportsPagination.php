<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Services;

use Patrikjak\Utils\Table\Dto\Pagination\Settings;
use Patrikjak\Utils\Table\Dto\Parameters;

interface SupportsPagination
{
    /**
     * @return array<string, string>
     */
    public function getHtmlParts(Parameters $parameters): array;

    public function getPaginationSettings(): Settings;
}