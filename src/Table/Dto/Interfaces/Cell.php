<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Interfaces;

use Patrikjak\Utils\Table\Enums\CellType;

interface Cell
{
    public function getType(): CellType;
}