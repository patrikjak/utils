<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\ColumnTypes;

use Patrikjak\Utils\Table\ColumnTypes\Interfaces\ColumnType;
use Patrikjak\Utils\View\Components\Table\Cells\ChipType;

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