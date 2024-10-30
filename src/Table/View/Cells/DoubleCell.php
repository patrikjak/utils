<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Cells;

use Patrikjak\Utils\Table\Dto\Cells\Double;
use Patrikjak\Utils\Table\Dto\Interfaces\ColumnType;
use Patrikjak\Utils\Table\Dto\Table;

class DoubleCell extends Cell
{
    public readonly string $additionalData;

    /**
     * @inheritDoc
     */
    public function __construct(
        public Table $table,
        public array $row,
        public string $dataKey,
        public ColumnType $columnType,
    ) {
        parent::__construct($table, $row, $dataKey, $columnType);

        $this->additionalData = $this->resolveAdditionalData();
    }

    private function resolveAdditionalData(): string
    {
        assert($this->columnType instanceof Double);

        return $this->row[$this->columnType->addition];
    }
}
