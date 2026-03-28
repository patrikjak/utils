<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Enums;

use Patrikjak\Utils\Common\Traits\EnumValues;

enum ButtonSize: string
{
    use EnumValues;

    case LG = 'lg';
    case MD = 'md';
    case SM = 'sm';
}
