<?php

namespace Patrikjak\Utils\Table\Services;

use Closure;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Cells\Chip;
use Patrikjak\Utils\Table\Dto\Cells\Double as DoubleCell;
use Patrikjak\Utils\Table\Dto\Cells\Simple;

readonly class Cell
{
    /**
     * Pass icon name for static icon - from resources/views/icons/
     * If the icon is dynamic, get icon from the row, you need to pass icon key in the row.
     */
    public static function simple(string $value): Simple
    {
        return new Simple($value);
    }

    public static function double(string $value, string $addition): DoubleCell
    {
        return new DoubleCell($value, $addition);
    }

    public static function chip(string $value, Type|Closure $type): Chip
    {
        return new Chip($value, $type);
    }
}