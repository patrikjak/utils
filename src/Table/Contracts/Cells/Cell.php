<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Contracts\Cells;

use Patrikjak\Utils\Table\Enums\Cells\CellType;

interface Cell
{
    public function getType(): CellType;
}
