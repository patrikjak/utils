<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Sort;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Sort\SortableColumn;
use Patrikjak\Utils\Table\Dto\Sort\SortCriteria;

class Sorter extends Component
{
    public array $selectedColumns = [];

    /**
     * @param array<SortableColumn> $sortableColumns
     * @param array<SortCriteria> $criteria
     */
    public function __construct(public array $sortableColumns, private readonly array $criteria = [])
    {
    }

    public function render(): View
    {
        $this->selectedColumns = array_map(
            static fn (SortCriteria $criteria) => $criteria->column,
            $this->criteria
        );

        return $this->view('pjutils::table.sort.sorter');
    }
}
