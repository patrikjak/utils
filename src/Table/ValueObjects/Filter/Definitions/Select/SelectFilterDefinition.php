<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\Select;

use Patrikjak\Utils\Common\Enums\Filter\FilterType;
use Patrikjak\Utils\Table\Contracts\Filter\FilterDefinition;
use Patrikjak\Utils\Table\Contracts\Filter\NeedsData;

readonly class SelectFilterDefinition implements FilterDefinition, NeedsData
{
    public function __construct(private string $dataUrl)
    {
    }

    public function getType(): FilterType
    {
        return FilterType::SELECT;
    }

    public function getDataUrl(): string
    {
        return $this->dataUrl;
    }
}
