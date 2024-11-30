<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Traits;

use ReflectionClass;

trait EnumValues
{
    /**
     * @return array<string, static>
     */
    public static function getAll(): array
    {
        $reflection = new ReflectionClass(static::class);

        return $reflection->getConstants();
    }

    /**
     * @return array<string>
     */
    public static function getKeys(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * @return array<string>
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}