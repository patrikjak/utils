<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Filter;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Patrikjak\Utils\Common\Dto\Filter\AbstractFilterCriteria;
use Patrikjak\Utils\Table\Dto\Filter\Definitions\FilterableColumn;
use Patrikjak\Utils\Table\Dto\Filter\Settings;

class Values extends Component
{
    /**
     * @var array<FilterOption>
     */
    public ?array $options;

    public function __construct(public readonly Settings $settings)
    {
    }

    public function render(): View
    {
        $this->options = $this->getOptions();

        return $this->view('pjutils::table.filter.values');
    }

    /**
     * @return array<FilterOption>
     */
    private function getOptions(): array
    {
        $options = [];
        $filterableColumnsCollection = new Collection($this->settings->filterableColumns);

        $labels = $filterableColumnsCollection->mapWithKeys(static function (FilterableColumn $column) {
            return [$column->column => $column->label];
        });

        foreach ($this->settings->criteria->filters as $filter) {
            assert($filter instanceof AbstractFilterCriteria);

            $label = $labels->get($filter->column);
            $options[] = new FilterOption($label, $filter);
        }

        return $options;
    }
}
