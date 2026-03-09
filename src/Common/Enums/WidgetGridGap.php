<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Enums;

use Patrikjak\Utils\Common\Traits\EnumValues;

enum WidgetGridGap: string
{
    use EnumValues;

    case SM = 'sm';
    case MD = 'md';
    case LG = 'lg';
}
