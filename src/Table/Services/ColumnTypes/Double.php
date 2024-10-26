<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Services\ColumnTypes;

use Patrikjak\Utils\Table\Enums\ColumnTypes\Type;
use Patrikjak\Utils\Table\Services\ColumnTypes\Interfaces\ColumnType;

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