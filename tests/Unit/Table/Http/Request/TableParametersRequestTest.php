<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Table\Http\Request;

use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class TableParametersRequestTest extends TestCase
{
    private const string TABLE_ID = 'table-id';

    public function testGetTableParametersDefault(): void
    {
        $request = new TableParametersRequest();

        $parameters = $request->getTableParameters();

        $this->assertSame(1, $parameters->page);
        $this->assertSame(10, $parameters->pageSize);
        $this->assertNull($parameters->sortCriteria);
        $this->assertNull($parameters->filterCriteria);
    }

    public function testGetTableParametersFromRequest(): void
    {
        $request = new TableParametersRequest([
            'page' => 2,
            'pageSize' => 20,
        ]);

        $parameters = $request->getTableParameters();

        $this->assertSame(2, $parameters->page);
        $this->assertSame(20, $parameters->pageSize);
    }

    public function testGetTableParametersSortCriteriaFromRequest(): void
    {
        $request = new TableParametersRequest([
            'sort' => 'name',
            'order' => 'desc',
        ]);

        $parameters = $request->getTableParameters();

        $this->assertSame(1, $parameters->page);
        $this->assertSame(10, $parameters->pageSize);
        $this->assertSame('name', $parameters->sortCriteria->column);
        $this->assertSame('desc', $parameters->sortCriteria->order->value);
    }

    public function testGetTableParametersWithDeleteSortCriteria(): void
    {
        $request = new TableParametersRequest([
            'sort' => 'name',
            'order' => 'desc',
            'deleteSort' => true,
        ]);

        $parameters = $request->getTableParameters();

        $this->assertNull($parameters->sortCriteria);
    }
}
