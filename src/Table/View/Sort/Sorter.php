<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Sort;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Sort\SortableColumn;

class Sorter extends Component
{
    /**
     * @param array<SortableColumn> $sortableColumns
     */
    public function __construct(public array $sortableColumns)
    {
    }

    public function render(): View
    {
        return $this->view('pjutils::table.sort.sorter');
    }
}
