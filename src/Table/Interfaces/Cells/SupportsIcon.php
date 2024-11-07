<?php

namespace Patrikjak\Utils\Table\Interfaces\Cells;

use Patrikjak\Utils\Common\Enums\Icon;

interface SupportsIcon
{
    public function getIcon(): ?Icon;
}