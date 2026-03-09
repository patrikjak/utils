<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Enums;

use Patrikjak\Utils\Common\Traits\EnumValues;

enum BadgeType: string
{
    use EnumValues;

    case DEFAULT = 'default';
    case SUCCESS = 'success';
    case DANGER = 'danger';
    case WARNING = 'warning';
    case INFO = 'info';
}
