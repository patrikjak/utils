<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Table;

class Toolbar extends Component
{
    public bool $showOptions = false;

    public function __construct(public Table $table)
    {
        $this->showOptions = $table->isFilterable()
            || $table->isSearchable()
            || $table->hasColumnVisibility();
    }

    public function render(): View
    {
        return view('pjutils::table.toolbar');
    }
}
