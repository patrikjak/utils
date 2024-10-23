<?php

namespace Patrikjak\Utils\View\Components;

enum Type: string
{
    case SUCCESS = 'success';
    case DANGER = 'danger';
    case WARNING = 'warning';
    case INFO = 'info';
    case NEUTRAL = 'light';
}
