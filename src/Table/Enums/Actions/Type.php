<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Enums\Actions;

enum Type: string
{
    case DEFAULT = 'default';

    case PRIMARY = 'primary';

    case SUCCESS = 'success';

    case DANGER = 'danger';

    case WARNING = 'warning';
}
