<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Interfaces;

use Patrikjak\Utils\Table\Enums\ColumnType as ColumnTypeEnum;

interface ColumnType
{
    public function getType(): ColumnTypeEnum;
}