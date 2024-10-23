<?php

namespace Patrikjak\Utils\View;

enum Type: string
{
    case SUCCESS = 'success';
    case DANGER = 'danger';
    case WARNING = 'warning';
    case INFO = 'info';
    case NEUTRAL = 'neutral';
}
