<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\Select;

use Patrikjak\Utils\Table\Contracts\Filter\FilterDefinition;
use Patrikjak\Utils\Table\Contracts\Filter\NeedsData;
use Patrikjak\Utils\Table\Enums\Filter\FilterType;

readonly class SelectFilterDefinition implements FilterDefinition, NeedsData
{
    public function __construct(private string $dataUrl)
    {
    }

    public function getType(): string
    {
        return FilterType::Select->value;
    }

    public function getDataUrl(): string
    {
        return $this->dataUrl;
    }

    public function getFilterData(): array
    {
        return ['options-url' => $this->dataUrl];
    }
}
