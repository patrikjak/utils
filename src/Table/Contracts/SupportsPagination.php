<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Contracts;

use Patrikjak\Utils\Table\Dto\Pagination\Settings;

interface SupportsPagination extends Renderable
{
    public function getPaginationSettings(): Settings;
}
