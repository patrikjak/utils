<?php

namespace Patrikjak\Utils\Table\Dto\Filter\Definitions\Text;

use Patrikjak\Utils\Table\Dto\Filter\Definitions\FilterDefinition;
use Patrikjak\Utils\Table\Dto\Filter\FilterType;

final readonly class TextFilterDefinition implements FilterDefinition
{
    public function getType(): FilterType
    {
        return FilterType::TEXT;
    }
}