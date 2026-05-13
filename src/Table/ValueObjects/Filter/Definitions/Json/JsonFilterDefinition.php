<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\Json;

use Patrikjak\Utils\Table\Contracts\Filter\FilterDefinition;
use Patrikjak\Utils\Table\Enums\Filter\FilterType;

readonly class JsonFilterDefinition implements FilterDefinition
{
    public function __construct(public ?string $jsonPath = null)
    {
    }

    public function getType(): string
    {
        return FilterType::Json->value;
    }

    public function getFilterData(): array
    {
        return ['json-path' => $this->jsonPath];
    }
}
