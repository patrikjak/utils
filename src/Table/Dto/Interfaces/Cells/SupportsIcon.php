<?php

namespace Patrikjak\Utils\Table\Dto\Interfaces\Cells;

use Patrikjak\Utils\Common\Enums\Icon;

interface SupportsIcon
{
    public function getIcon(): ?Icon;
}