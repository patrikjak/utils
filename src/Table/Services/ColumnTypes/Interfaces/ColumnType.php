<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Services\ColumnTypes\Interfaces;

use Patrikjak\Utils\Table\Enums\ColumnTypes\Type;

interface ColumnType
{
    public function getType(): Type;
}