<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Enums\Filter;

enum FilterType: string
{
    case Text = 'text';
    case Select = 'select';
    case Date = 'date';
    case Number = 'number';
    case Json = 'json';
}
