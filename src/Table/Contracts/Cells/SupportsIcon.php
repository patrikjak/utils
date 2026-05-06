<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Contracts\Cells;

use Patrikjak\Utils\Common\Icon;

interface SupportsIcon
{
    public function getIcon(): ?Icon;
}
