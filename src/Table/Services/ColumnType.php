<?php

namespace Patrikjak\Utils\Table\Services;

use Closure;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Cells\Chip;
use Patrikjak\Utils\Table\Dto\Cells\Double as DoubleCell;
use Patrikjak\Utils\Table\Dto\Cells\Simple;
use Patrikjak\Utils\Table\Enums\ColumnTypes\IconType;

readonly class ColumnType
{
    /**
     * Pass icon name for static icon - from resources/views/icons/
     * If the icon is dynamic, get icon from the row, you need to pass icon key in the row.
     */
    public static function simple(null|string|Closure $icon = null, IconType $iconType = IconType::STATIC): Simple
    {
        if ($icon instanceof Closure) {
            $icon = $icon();
        }

        return new Simple($icon, $iconType);
    }

    public static function double(string $addition): DoubleCell
    {
        return new DoubleCell($addition);
    }

    public static function chip(Type|Closure $type): Chip
    {
        return new Chip($type);
    }
}