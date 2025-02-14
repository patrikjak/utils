<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Sort;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Sort\SortableColumn;
use Patrikjak\Utils\Table\Dto\Sort\SortCriteria;

class Values extends Component
{
    /**
     * @var array<SortOption>
     */
    public array $options = [];

    /**
     * @param array<SortCriteria> $values
     * @param array<SortableColumn> $sortableColumns
     */
    public function __construct(private readonly array $values, private readonly array $sortableColumns)
    {
    }

    public function render(): View
    {
        $sortableColumnsCollection = new Collection($this->sortableColumns);
        $valuesCollection = new Collection($this->values);

        $labels = $sortableColumnsCollection->mapWithKeys(function (SortableColumn $column) {
            return [$column->column => $column->label];
        });

        $this->options = array_filter(array_map(function (SortCriteria $criteria) use ($labels): ?SortOption {
            $label = $labels->get($criteria->column);

            if ($label === null) {
                return null;
            }

            assert(is_string($label));

            return new SortOption($criteria, $label);
        }, $valuesCollection->toArray()));

        return $this->view('pjutils::table.sort.values');
    }
}
