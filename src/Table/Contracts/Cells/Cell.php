<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Contracts\Cells;

interface Cell
{
    public function getType(): string;
}
