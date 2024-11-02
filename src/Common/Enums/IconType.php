<?php

namespace Patrikjak\Utils\Common\Enums;

enum IconType: string
{
    case SUCCESS = 'success';
    case DANGER = 'danger';
    case WARNING = 'warning';
    case INFO = 'info';
    case NEUTRAL = 'neutral';

    case WHITE = 'white';
}