<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\ColumnTypes\Interfaces;

enum IconType: string
{
    case STATIC = 'static';

    case DYNAMIC = 'dynamic';
}
