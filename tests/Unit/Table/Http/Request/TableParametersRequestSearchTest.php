<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Table\Http\Request;

use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\SearchFilterCriteria;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;
use Patrikjak\Utils\Tests\Unit\TestCase;

final class TableParametersRequestSearchTest extends TestCase
{
    public function testGetTableParametersSearchQueryProducesSearchFilterCriteria(): void
    {
        $request = new TableParametersRequest(['search' => 'john']);

        $parameters = $request->getTableParameters();

        $filters = $parameters->filterCriteria?->filters ?? [];
        $searchCriteria = collect($filters)->first(static fn (mixed $f) => $f instanceof SearchFilterCriteria);

        $this->assertInstanceOf(SearchFilterCriteria::class, $searchCriteria);
        $this->assertSame('john', $searchCriteria->value);
        $this->assertSame([], $searchCriteria->searchableColumns);
    }

    public function testGetTableParametersDeleteSearchClearsSearchCriteria(): void
    {
        $request = new TableParametersRequest(['deleteSearch' => 'true']);

        $parameters = $request->getTableParameters();

        $filters = $parameters->filterCriteria?->filters ?? [];
        $searchCriteria = collect($filters)->first(static fn (mixed $f) => $f instanceof SearchFilterCriteria);

        $this->assertNull($searchCriteria);
    }

    public function testGetTableParametersNoSearchProducesNoSearchCriteria(): void
    {
        $request = new TableParametersRequest();

        $parameters = $request->getTableParameters();

        $filters = $parameters->filterCriteria?->filters ?? [];
        $searchCriteria = collect($filters)->first(static fn (mixed $f) => $f instanceof SearchFilterCriteria);

        $this->assertNull($searchCriteria);
    }

    public function testSearchAndFilterCriteriaAreMerged(): void
    {
        $request = new TableParametersRequest([
            'search' => 'alice',
            'filter' => [
                'status' => [['type' => 'select', 'value' => 'active']],
            ],
        ]);

        $parameters = $request->getTableParameters();

        $filters = $parameters->filterCriteria?->filters ?? [];
        $this->assertCount(2, $filters);

        $searchCriteria = collect($filters)->first(static fn (mixed $f) => $f instanceof SearchFilterCriteria);
        $this->assertInstanceOf(SearchFilterCriteria::class, $searchCriteria);
        $this->assertSame('alice', $searchCriteria->value);
    }
}
