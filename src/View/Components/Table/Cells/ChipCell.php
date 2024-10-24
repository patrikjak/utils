<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components\Table\Cells;

use Patrikjak\Utils\Table\ColumnTypes\Chip;
use Patrikjak\Utils\Table\ColumnTypes\Interfaces\ColumnType;
use Patrikjak\Utils\Table\Table;

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
