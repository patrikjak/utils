<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Interfaces\ColumnType;
use Patrikjak\Utils\Table\Dto\Table;
use Patrikjak\Utils\Table\Enums\ColumnType as ColumnTypeEnum;
use Patrikjak\Utils\Table\View\Traits\TableMethods;
use stdClass;

class Row extends Component
{
    use TableMethods;

    public readonly string $rowId;

    public readonly ?string $rowClass;

    /**
     * @param array<string, scalar|array<string>> $row
     */
    public function __construct(public Table $table, public array $row, public stdClass $loop)
    {
        $this->rowId = $this->resolveRowId();
        $this->rowClass = isset($row['rowClass']) ? implode(' ', $row['rowClass']) : null;
    }

    public function render(): View
    {
        return view('pjutils::table.row');
    }

    public function getCell(ColumnType $type): string
    {
        return 'pjutils.table::cells.' . match($type->getType()) {
            ColumnTypeEnum::DOUBLE => 'double-cell',
            ColumnTypeEnum::CHIP => 'chip-cell',
            default => 'simple-cell',
        };
    }

    private function resolveRowId(): string
    {
        return $this->row[$this->table->rowId];
    }
}
