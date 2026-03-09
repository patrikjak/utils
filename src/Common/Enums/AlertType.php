<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\Enums;

use Patrikjak\Utils\Common\Traits\EnumValues;

enum AlertType: string
{
    use EnumValues;

    case INFO = 'info';
    case SUCCESS = 'success';
    case DANGER = 'danger';
    case WARNING = 'warning';
}
