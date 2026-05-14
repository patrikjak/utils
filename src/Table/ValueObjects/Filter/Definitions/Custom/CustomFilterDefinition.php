<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\Custom;

use Patrikjak\Utils\Table\Contracts\Filter\FilterDefinition;

readonly class CustomFilterDefinition implements FilterDefinition
{
    public function __construct(private string $type)
    {
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array<string, string|int|float|null>
     */
    public function getFilterData(): array
    {
        return [];
    }
}
