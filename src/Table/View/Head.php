<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Table;
use Patrikjak\Utils\Table\View\Traits\TableMethods;

class Head extends Component
{
    use TableMethods;

    /**
     * @var array<string, string>
     */
    public readonly array $headerData;

    /**
     * @var array<int, string>
     */
    public readonly array $sortableColumnKeys;

    public readonly ?string $activeSortColumn;

    public readonly ?string $activeSortOrder;

    public function __construct(public Table $table)
    {
        $this->headerData = $this->getHeaderData();
        $this->sortableColumnKeys = $this->getSortableColumnKeys();
        $this->activeSortColumn = $table->sortSettings?->criteria?->column;
        $this->activeSortOrder = $table->sortSettings?->criteria?->order->value;
    }

    public function render(): View
    {
        return view('pjutils::table.head');
    }

    /**
     * @return array<string, string>
     */
    private function getHeaderData(): array
    {
        return $this->table->columns
            ->mapWithKeys(fn (string $column) => [$column => $this->table->header[$column]])
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function getSortableColumnKeys(): array
    {
        if ($this->table->sortSettings === null) {
            return [];
        }

        return $this->table->sortSettings->sortableColumns
            ->map(static fn ($col) => $col->column)
            ->all();
    }
}
