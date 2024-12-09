<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Services;

enum TelephonePattern: string
{
    case SK = '/^\+421[0-9]{9}$/';
    case CZ = '/^\+420[0-9]{9}$/';

    public function getExample(): string
    {
        return match ($this) {
            self::SK => '+421123456789',
            self::CZ => '+420123456789',
        };
    }
}
