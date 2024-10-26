<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Services\ColumnTypes;

use Patrikjak\Utils\Table\Enums\ColumnTypes\ChipType;

final readonly class ChipInfo
{
    public function __construct(public string $inputValue, public ChipType $type, public ?string $label = null)
    {
    }
}