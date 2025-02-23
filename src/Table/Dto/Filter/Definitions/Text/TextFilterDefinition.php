<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Dto\Filter\Definitions\Text;

use Patrikjak\Utils\Common\Enums\Filter\FilterType;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\FilterDefinition;

final readonly class TextFilterDefinition implements FilterDefinition
{
    public function getType(): FilterType
    {
        return FilterType::TEXT;
    }
}