<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Sort;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Sort\Settings;

class Sorter extends Component
{
    public ?string $selectedColumn = null;

    public function __construct(public readonly Settings $settings)
    {
    }

    public function render(): View
    {
        $this->selectedColumn = $this->settings->criteria?->column;

        return $this->view('pjutils::table.sort.sorter');
    }
}
