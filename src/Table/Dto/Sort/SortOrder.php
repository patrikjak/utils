<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Sort;

enum SortOrder: string
{
    case ASC = 'asc';
    case DESC = 'desc';
}
