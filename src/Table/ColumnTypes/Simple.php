<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\ColumnTypes;

use Patrikjak\Utils\Table\ColumnTypes\Interfaces\ColumnType;
use Patrikjak\Utils\Table\ColumnTypes\Interfaces\IconType;
use Patrikjak\Utils\Table\ColumnTypes\Interfaces\SupportsIcon;

final readonly class Simple implements ColumnType, SupportsIcon
{
    /**
     * Pass icon name for static icon - from resources/views/icons/
     * If the icon is dynamic, get icon from the row, you need to pass icon key in the row.
     */
    public function __construct(private ?string $icon = null, private IconType $iconType = IconType::STATIC)
    {
    }

    public function getType(): Type
    {
        return Type::SIMPLE;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getIconType(): IconType
    {
        return $this->iconType;
    }
}