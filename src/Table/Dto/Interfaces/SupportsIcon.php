<?php

namespace Patrikjak\Utils\Table\Dto\Interfaces;

use Patrikjak\Utils\Common\Enums\Icon;

interface SupportsIcon
{
    public function getIcon(): ?Icon;
}