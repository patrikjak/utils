<?php

namespace Patrikjak\Utils\Table\Dto\Cells;

use Patrikjak\Utils\Table\Dto\Interfaces\ColumnType;
use Patrikjak\Utils\Table\Enums\ColumnType as ColumnTypeEnum;

final readonly class Double implements ColumnType
{
    /**
     * @param string $addition Array key from getData method
     */
    public function __construct(public string $addition)
    {
    }

    public function getType(): ColumnTypeEnum
    {
        return ColumnTypeEnum::DOUBLE;
    }
}