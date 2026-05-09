<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Enums\Cells;

enum CellType: string
{
    case Simple = 'simple';

    case TwoLine = 'two-line';

    case Chip = 'chip';

    case Link = 'link';
}
