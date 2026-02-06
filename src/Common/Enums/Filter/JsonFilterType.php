<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\Enums\Filter;

enum JsonFilterType: string
{
    case CONTAINS = 'contains';
    case NOT_CONTAINS = 'not_contains';
    case EQUALS = 'equals';
    case NOT_EQUALS = 'not_equals';
    case STARTS_WITH = 'starts_with';
    case ENDS_WITH = 'ends_with';

    public function toLabel(): string
    {
        return __('pjutils::table.json_filter_types.' . $this->value);
    }
}
