<?php

namespace Patrikjak\Utils\Table\Services;

use Closure;
use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Cells\Chip;
use Patrikjak\Utils\Table\Dto\Cells\Double as DoubleCell;
use Patrikjak\Utils\Table\Dto\Cells\Simple;

readonly class Cell
{
    public static function simple(string $value, ?Icon $icon = null): Simple
    {
        return new Simple($value, $icon);
    }

    public static function double(string $value, string $addition): DoubleCell
    {
        return new DoubleCell($value, $addition);
    }

    public static function chip(string $value, Type|Closure $type = Type::NEUTRAL): Chip
    {
        return new Chip($value, $type);
    }
}