<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Table as TableDto;
use Patrikjak\Utils\Table\View\Traits\TableMethods;

class Table extends Component
{
    use TableMethods;

    public readonly string $tableClass;

    public readonly string $tableId;

    public readonly Table $component;

    public bool $showOptions = false;

    public function __construct(public TableDto $table)
    {
        $this->tableClass = $this->resolveTableClass();
        $this->tableId = $this->table->tableId;
        $this->component = $this;
    }

    public function render(): View
    {
        $this->showOptions = $this->table->isSortable() || $this->table->isFilterable();

        return view('pjutils::table.table');
    }

    private function resolveTableClass(): string
    {
        $classes = ['pj-table'];

        if ($this->table->expandable !== null) {
            $classes[] = 'expandable';
        }

        return implode(' ', $classes);
    }
}
