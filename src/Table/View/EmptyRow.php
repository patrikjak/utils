<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\EmptyState;
use Patrikjak\Utils\Table\Dto\Table;

class EmptyRow extends Component
{
    public readonly int $colspan;

    public readonly string $title;

    public readonly ?string $description;

    public readonly ?string $icon;

    public function __construct(public Table $table)
    {
        $this->colspan = $this->resolveColspan();

        $emptyState = $table->emptyState;

        $this->title = $emptyState !== null ? $emptyState->title : __('pjutils::table.no_data_available');
        $this->description = $emptyState?->description;
        $this->icon = $emptyState?->icon;
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
