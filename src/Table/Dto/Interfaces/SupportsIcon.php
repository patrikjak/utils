<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Interfaces;

use Patrikjak\Utils\Table\Enums\IconType;

interface SupportsIcon
{
    public function getIcon(): ?string;

    public function getIconType(): IconType;
}