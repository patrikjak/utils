<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Cells\Cell;
use Patrikjak\Utils\Table\Dto\Table;
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

    public function getCellView(Cell $cell): string
    {
        return sprintf('pjutils.table::cells.%s', $cell->getType()->value);
    }

    private function resolveRowId(): string
    {
        return (string) $this->row[$this->table->rowId];
    }
}
