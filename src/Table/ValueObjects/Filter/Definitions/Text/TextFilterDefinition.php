<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\Text;

use Patrikjak\Utils\Table\Contracts\Filter\FilterDefinition;
use Patrikjak\Utils\Table\Enums\Filter\FilterType;

readonly class TextFilterDefinition implements FilterDefinition
{
    public function getType(): string
    {
        return FilterType::Text->value;
    }

    /**
     * @return array<string, string|int|float|null>
     */
    public function getFilterData(): array
    {
        return [];
    }
}
