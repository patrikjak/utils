<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dtos\Table;
use Patrikjak\Utils\Table\Enums\ColumnTypes\Type;
use Patrikjak\Utils\Table\Services\ColumnTypes\Interfaces\ColumnType;
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
            Type::DOUBLE => 'double-cell',
            Type::CHIP => 'chip-cell',
            default => 'simple-cell',
        };
    }

    private function resolveRowId(): string
    {
        return $this->row[$this->table->rowId];
    }
}
