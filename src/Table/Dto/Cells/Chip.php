<?php

namespace Patrikjak\Utils\Table\Dto\Cells;

use Closure;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Interfaces\ColumnType;
use Patrikjak\Utils\Table\Enums\ColumnTypes\Type as ColumnTypeEnum;

final readonly class Chip implements ColumnType
{
    public function __construct(public Type|Closure $type)
    {
    }

    public function getType(): ColumnTypeEnum
    {
        return ColumnTypeEnum::CHIP;
    }
}