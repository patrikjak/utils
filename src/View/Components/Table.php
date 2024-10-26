<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dtos\Table as TableService;
use Patrikjak\Utils\View\Components\Table\TableMethods;

class Table extends Component
{
    use TableMethods;

    public readonly string $tableClass;

    public readonly string $tableId;
    public readonly Table $component;

    public function __construct(public TableService $table)
    {
        $this->tableClass = $this->resolveTableClass();
        $this->tableId = $this->table->tableId;
        $this->component = $this;
    }

    public function render(): View
    {
        return view('pjutils::components.table');
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
