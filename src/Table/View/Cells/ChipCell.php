<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Cells;

use Closure;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Common\Exceptions\InvalidTypeException;
use Patrikjak\Utils\Table\Dto\Cells\Chip;
use Patrikjak\Utils\Table\Dto\Interfaces\ColumnType;
use Patrikjak\Utils\Table\Dto\Table;

class ChipCell extends Cell
{
    public readonly string $label;

    public readonly string $chipType;

    /**
     * @inheritDoc
     * @throws InvalidTypeException
     */
    public function __construct(
        public Table $table,
        public array $row,
        public string $dataKey,
        public ColumnType $columnType,
    ) {
        parent::__construct($table, $row, $dataKey, $columnType);

        assert($this->columnType instanceof Chip);

        $this->label = $this->resolveLabel();
        $this->chipType = $this->resolveChipType();
    }

    private function resolveLabel(): string
    {
        return $this->row[$this->dataKey];
    }

    /**
     * @throws InvalidTypeException
     */
    private function resolveChipType(): string
    {
        assert($this->columnType instanceof Chip);

        $type = $this->columnType->type;

        if (!$type instanceof Closure) {
            return $this->columnType->type->value;
        }

        $type = $type($this->row);

        if (!$type instanceof Type) {
            throw new InvalidTypeException();
        }

        return $type->value;
    }
}
