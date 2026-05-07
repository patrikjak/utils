<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\Json;

use Patrikjak\Utils\Common\Enums\Filter\FilterType;
use Patrikjak\Utils\Table\Contracts\Filter\FilterDefinition;

readonly class JsonFilterDefinition implements FilterDefinition
{
    public function __construct(public ?string $jsonPath = null)
    {
    }

    public function getType(): FilterType
    {
        return FilterType::JSON;
    }
}
