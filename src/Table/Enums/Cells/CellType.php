<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Enums\Cells;

enum CellType: string
{
    case SIMPLE = 'simple';

    case DOUBLE = 'double';

    case CHIP = 'chip';

    case LINK = 'link';
}
