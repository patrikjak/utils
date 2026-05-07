<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\View\Filter;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Patrikjak\Utils\Common\ValueObjects\Filter\AbstractFilterCriteria;
use Patrikjak\Utils\Common\ValueObjects\Filter\JsonFilterCriteria;
use Patrikjak\Utils\Table\Dto\Filter\Settings;
use Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\FilterableColumn;
use Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\Json\JsonFilterDefinition;

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

        foreach ($this->settings->criteria->filters as $filter) {
            $label = $this->getLabelForFilter($filter, $filterableColumnsCollection);

            if ($label === null) {
                continue;
            }

            $options[] = new FilterOption($label, $filter);
        }

        return $options;
    }

    /**
     * @param Collection<int, FilterableColumn> $filterableColumns
     */
    private function getLabelForFilter(AbstractFilterCriteria $filter, Collection $filterableColumns): ?string
    {
        if ($filter instanceof JsonFilterCriteria) {
            $match = $filterableColumns->first(
                static fn (FilterableColumn $col) => $col->column === $filter->column
                    && $col->filterDefinition instanceof JsonFilterDefinition
                    && $col->filterDefinition->jsonPath === $filter->jsonPath,
            );

            $match ??= $filterableColumns->first(
                static fn (FilterableColumn $col) => $col->column === $filter->column
                    && $col->filterDefinition instanceof JsonFilterDefinition,
            );

            return $match?->label;
        }

        return $filterableColumns
            ->mapWithKeys(static fn (FilterableColumn $col) => [$col->column => $col->label])
            ->get($filter->column);
    }
}
