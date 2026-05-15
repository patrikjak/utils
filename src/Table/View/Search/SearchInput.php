<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\View\Search;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\SearchFilterCriteria;
use Patrikjak\Utils\Table\Dto\Filter\Settings;

class SearchInput extends Component
{
    public ?string $searchQuery;

    public function __construct(public readonly Settings $filterSettings)
    {
        $searchCriteria = $filterSettings->criteria?->filters
            ? new Collection($filterSettings->criteria->filters)->first(
                static fn (mixed $filter) => $filter instanceof SearchFilterCriteria,
            )
            : null;

        $this->searchQuery = $searchCriteria instanceof SearchFilterCriteria ? $searchCriteria->value : null;
    }

    public function render(): View
    {
        return $this->view('pjutils::table.search.search-input');
    }
}
