<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Interfaces\Cells;

use Patrikjak\Utils\Table\Enums\CellType;

interface Cell
{
    public function getType(): CellType;
}