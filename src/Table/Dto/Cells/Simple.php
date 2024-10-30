<?php

namespace Patrikjak\Utils\Table\Dto\Cells;

use Patrikjak\Utils\Table\Dto\Interfaces\ColumnType;
use Patrikjak\Utils\Table\Dto\Interfaces\SupportsIcon;
use Patrikjak\Utils\Table\Enums\ColumnTypes\IconType;
use Patrikjak\Utils\Table\Enums\ColumnTypes\Type;

final readonly class Simple implements ColumnType, SupportsIcon
{
    /**
     * Pass icon name for static icon - from resources/views/icons/
     * If the icon is dynamic, get icon from the row, you need to pass icon key in the row.
     */
    public function __construct(public ?string $icon = null, public IconType $iconType = IconType::STATIC)
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