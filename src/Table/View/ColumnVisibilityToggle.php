<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\View;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
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
        $visibleHeaderKeys = $table->header->keys();

        $this->visibleColumns = new Collection(array_keys($table->columnVisibility->columns))
            ->filter(static fn (string $key) => $visibleHeaderKeys->contains($key))
            ->values()
            ->all();
    }

    public function render(): View
    {
        return view('pjutils::table.column-visibility-toggle');
    }
}
