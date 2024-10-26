<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Services\ColumnTypes;

use Patrikjak\Utils\Table\Enums\ColumnTypes\ChipType;
use Patrikjak\Utils\Table\Enums\ColumnTypes\Type;
use Patrikjak\Utils\Table\Services\ColumnTypes\Interfaces\ColumnType;

final readonly class Chip implements ColumnType
{
    /**
     * @param array<ChipInfo> $mapping
     */
    public function __construct(private array $mapping)
    {
    }

    public function getType(): Type
    {
        return Type::CHIP;
    }
    
    /**
     * @return array<string, array{label: string, type: ChipType}>
     */
    public function getMapped(): array
    {
        return collect($this->mapping)->mapWithKeys(
            static fn (ChipInfo $chipInfo) => [
                $chipInfo->inputValue => [
                    'label' => $chipInfo->label ?? $chipInfo->inputValue,
                    'type' => $chipInfo->type,
                ],
            ],
        )->toArray();
    }
}