<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components\Table;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Table;

class EmptyRow extends Component
{
    public readonly int $colspan;

    public function __construct(public Table $table, public ?string $message = null)
    {
        $this->colspan = $this->resolveColspan();
        $this->message ??= __('pjutils::table.no_data_available');
    }

    public function render(): View
    {
        return view('pjutils::components.table.empty-row');
    }

    private function resolveColspan(): int
    {
        return count($this->table->header)
            + ($this->table->showCheckboxes ? 1 : 0)
            + ($this->table->showCheckboxes ? 1 : 0)
            + ($this->table->hasActions() ? 1 : 0);
    }
}
