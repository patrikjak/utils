<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Cells;

use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Cells\Double as DoubleCell;

readonly class CellFactory
{
    public static function simple(string $value, ?Icon $icon = null): Simple
    {
        return new Simple($value, $icon);
    }

    public static function double(string $value, string $addition): DoubleCell
    {
        return new DoubleCell($value, $addition);
    }

    public static function chip(string $value, Type $type = Type::NEUTRAL): Chip
    {
        return new Chip($value, $type);
    }
}