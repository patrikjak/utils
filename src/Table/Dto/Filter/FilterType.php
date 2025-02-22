<?php

namespace Patrikjak\Utils\Table\Dto\Filter;

enum FilterType: string
{
    case TEXT = 'text';
    case SELECT = 'select';
    case DATE = 'date';
    case NUMBER = 'number';
}