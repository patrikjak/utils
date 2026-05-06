<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\Text;

use Patrikjak\Utils\Common\Enums\Filter\FilterType;
use Patrikjak\Utils\Table\Contracts\Filter\FilterDefinition;

final readonly class TextFilterDefinition implements FilterDefinition
{
    public function getType(): FilterType
    {
        return FilterType::TEXT;
    }
}
