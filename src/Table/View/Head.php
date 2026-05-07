<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Table;
use Patrikjak\Utils\Table\ValueObjects\Sort\SortableColumn;
use Patrikjak\Utils\Table\View\Traits\TableMethods;

class Head extends Component
{
    use TableMethods;

    /**
     * @var array<string, string>
     */
    public readonly array $headerData;

    /**
     * @var array<string>
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
        $header = [];
        $headerData = $this->table->header;

        foreach ($this->table->columns as $column) {
            $header[$column] = $headerData[$column];
        }

        return $header;
    }

    /**
     * @return array<string>
     */
    private function getSortableColumnKeys(): array
    {
        if ($this->table->sortSettings === null) {
            return [];
        }

        return array_map(
            static fn (SortableColumn $sortableColumn) => $sortableColumn->column,
            $this->table->sortSettings->sortableColumns,
        );
    }
}
