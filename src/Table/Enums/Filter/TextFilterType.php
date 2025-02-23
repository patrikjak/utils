<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Enums\Filter;

enum TextFilterType: string
{
    case CONTAINS = 'contains';
    case EQUALS = 'equals';
    case STARTS_WITH = 'starts_with';
    case ENDS_WITH = 'ends_with';

    public function toLabel(): string
    {
        return __('pjutils::table.text_filter_types.' . $this->value);
    }
}
