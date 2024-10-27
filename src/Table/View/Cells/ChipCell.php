<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Cells;

use Patrikjak\Utils\Table\Dtos\Table;
use Patrikjak\Utils\Table\Services\ColumnTypes\Chip;
use Patrikjak\Utils\Table\Services\ColumnTypes\Interfaces\ColumnType;

class ChipCell extends Cell
{
    public readonly string $label;

    public readonly string $chipType;

    /**
     * @var array<string, array{label: string, type: ChipType}>
     */
    private array $mappedChipInfo;

    /**
     * @inheritDoc
     */
    public function __construct(
        public Table $table,
        public array $row,
        public string $dataKey,
        public ColumnType $type,
    ) {
        parent::__construct($table, $row, $dataKey, $type);

        assert($this->type instanceof Chip);
        $this->mappedChipInfo = $this->type->getMapped();

        $this->label = $this->resolveLabel();
        $this->chipType = $this->resolveChipType();
    }

    private function resolveLabel(): string
    {
        return $this->mappedChipInfo[$this->row[$this->dataKey]]['label'];
    }

    private function resolveChipType(): string
    {
        return $this->mappedChipInfo[$this->row[$this->dataKey]]['type']->value;
    }
}
