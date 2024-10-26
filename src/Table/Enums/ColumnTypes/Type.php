<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Enums\ColumnTypes;

enum Type: string
{
    case SIMPLE = 'simple';

    case DOUBLE = 'double';

    case CHIP = 'chip';
}
