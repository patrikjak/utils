<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Cells;

enum ChipType: string
{
    case SUCCESS = 'success';

    case DANGER = 'danger';

    case WARNING = 'warning';

    case INFO = 'info';
}
