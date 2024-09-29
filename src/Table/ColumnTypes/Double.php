<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\ColumnTypes;

use Patrikjak\Utils\Table\ColumnTypes\Interfaces\ColumnType;

final readonly class Double implements ColumnType
{
    public function __construct(private string $addition)
    {
    }

    public function getType(): Type
    {
        return Type::DOUBLE;
    }

    public function getAddition(): string
    {
        return $this->addition;
    }
}