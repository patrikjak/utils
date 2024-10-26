<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components\Table\Cells;

use Patrikjak\Utils\Table\Dtos\Table;
use Patrikjak\Utils\Table\Services\ColumnTypes\Double;
use Patrikjak\Utils\Table\Services\ColumnTypes\Interfaces\ColumnType;

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
        public ColumnType $type,
    ) {
        parent::__construct($table, $row, $dataKey, $type);

        $this->additionalData = $this->resolveAdditionalData();
    }

    private function resolveAdditionalData(): string
    {
        assert($this->type instanceof Double);

        return $this->row[$this->type->getAddition()];
    }
}
