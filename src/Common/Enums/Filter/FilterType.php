<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Enums\Filter;

enum FilterType: string
{
    case TEXT = 'text';
    case SELECT = 'select';
    case DATE = 'date';
    case NUMBER = 'number';
}