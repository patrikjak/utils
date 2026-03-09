<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Enums;

use Patrikjak\Utils\Common\Traits\EnumValues;

enum WidgetSize: string
{
    use EnumValues;

    case XS = 'xs';
    case SM = 'sm';
    case MD = 'md';
    case FULL = 'full';
}
