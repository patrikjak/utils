<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Table;

class ColumnVisibilityToggle extends Component
{
    /**
     * @var array<string>
     */
    public readonly array $visibleColumns;

    public function __construct(public Table $table)
    {
        $visibleHeaderKeys = array_keys($table->header);

        $this->visibleColumns = array_values(
            array_filter(
                array_keys($table->columnVisibility->columns),
                static fn (string $key) => in_array($key, $visibleHeaderKeys, strict: true),
            ),
        );
    }

    public function render(): View
    {
        return view('pjutils::table.column-visibility-toggle');
    }
}
