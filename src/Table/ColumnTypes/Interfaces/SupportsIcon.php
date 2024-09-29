<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\ColumnTypes\Interfaces;

interface SupportsIcon
{
    public function getIcon(): ?string;

    public function getIconType(): IconType;
}