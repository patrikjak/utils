<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\ColumnTypes\Interfaces;

use Patrikjak\Utils\Table\ColumnTypes\Type;

interface ColumnType
{
    public function getType(): Type;
}