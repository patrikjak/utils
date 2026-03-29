<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Table\Http\Request;

use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class TableParametersRequestSearchTest extends TestCase
{
    private const string TABLE_ID = 'table-id';

    public function testGetTableParametersSearchQueryFromRequest(): void
    {
        $request = new TableParametersRequest(['search' => 'john']);

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertSame('john', $parameters->searchQuery);
    }

    public function testGetTableParametersDeleteSearchClearsQuery(): void
    {
        $request = new TableParametersRequest(['deleteSearch' => 'true']);

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertNull($parameters->searchQuery);
    }

    public function testGetTableParametersSearchQueryIsNullByDefault(): void
    {
        $request = new TableParametersRequest();

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertNull($parameters->searchQuery);
    }
}
