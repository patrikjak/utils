<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Sort;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Sort\Settings;
use Patrikjak\Utils\Table\Dto\Sort\SortableColumn;

class Values extends Component
{
    public ?SortOption $option;

    public function __construct(private readonly Settings $settings)
    {
    }

    public function render(): View
    {
        $this->option = $this->getSortOption();

        return $this->view('pjutils::table.sort.values');
    }

    private function getSortOption(): SortOption
    {
        $sortableColumnsCollection = new Collection($this->settings->sortableColumns);

        $labels = $sortableColumnsCollection->mapWithKeys(static function (SortableColumn $column) {
            return [$column->column => $column->label];
        });

        $label = $labels->get($this->settings->criteria->column);

        return new SortOption($this->settings->criteria, $label);
    }
}
