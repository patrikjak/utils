<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\ColumnTypes;

final readonly class ChipInfo
{
    public function __construct(public string $inputValue, public ChipType $type, public ?string $label = null)
    {
    }
}