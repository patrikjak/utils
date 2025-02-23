<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Table;

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
        return view('pjutils::table.empty-row');
    }

    private function resolveColspan(): int
    {
        return count($this->table->header)
            + ($this->table->showOrder ? 1 : 0)
            + ($this->table->showCheckboxes ? 1 : 0)
            + ($this->table->hasActions() ? 1 : 0);
    }
}
