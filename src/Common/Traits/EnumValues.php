<?php

namespace Patrikjak\Utils\Common\Traits;

use ReflectionClass;

trait EnumValues
{
    public static function getAll(): array
    {
        $reflection = new ReflectionClass(static::class);

        return $reflection->getConstants();
    }

    public static function getKeys(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}