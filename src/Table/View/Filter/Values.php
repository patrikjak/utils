<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\View\Filter;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Registry\FilterStrategyRegistry;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\SearchFilterCriteria;
use Patrikjak\Utils\Table\Dto\Filter\Settings;
use Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\FilterableColumn;

class Values extends Component
{
    /**
     * @var array<FilterOption>
     */
    public ?array $options;

    public function __construct(
        public readonly Settings $settings,
        private readonly FilterStrategyRegistry $filterStrategyRegistry,
    ) {
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

        foreach ($this->settings->criteria->filters as $filter) {
            if ($filter instanceof SearchFilterCriteria) {
                continue;
            }

            $label = $this->getLabelForFilter($filter);

            if ($label === null) {
                continue;
            }

            $chipView = $this->filterStrategyRegistry->getChipView($filter->getType());

            if ($chipView === null) {
                continue;
            }

            $options[] = new FilterOption($label, $filter, $chipView);
        }

        return $options;
    }

    private function getLabelForFilter(AbstractFilterCriteria $filter): ?string
    {
        $match = $this->settings->filterableColumns->first(
            static fn (FilterableColumn $column) => $column->column === $filter->column,
        );

        return $match?->label;
    }
}
