<?php

namespace Patrikjak\Utils\Tests\Unit\Table\Http\Request;

use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;
use Orchestra\Testbench\TestCase;

class TableParametersRequestTest extends TestCase
{
    private const string TABLE_ID = 'table-id';

    public function testGetTableParametersDefault(): void
    {
        $request = new TableParametersRequest();

        $parameters = $request->getTableParameters(self::TABLE_ID);

        self::assertSame(1, $parameters->page);
        self::assertSame(10, $parameters->pageSize);
    }

    public function testGetTableParametersFromRequest(): void
    {
        $request = new TableParametersRequest([
            'page' => 2,
            'pageSize' => 20,
        ]);

        $parameters = $request->getTableParameters(self::TABLE_ID);

        self::assertSame(2, $parameters->page);
        self::assertSame(20, $parameters->pageSize);
    }

    public function testGetTableParametersFromCookie(): void
    {
        $request = new TableParametersRequest(cookies: []);
        $request->cookies->set(self::TABLE_ID, json_encode(['page' => 3, 'pageSize' => 30]));

        $parameters = $request->getTableParameters(self::TABLE_ID);

        self::assertSame(3, $parameters->page);
        self::assertSame(30, $parameters->pageSize);
    }

    public function testGetTableParametersPageFromRequest(): void
    {
        $request = new TableParametersRequest([
            'page' => 4,
        ]);
        $request->cookies->set(self::TABLE_ID, json_encode(['page' => 5, 'pageSize' => 50]));

        $parameters = $request->getTableParameters(self::TABLE_ID);

        self::assertSame(4, $parameters->page);
        self::assertSame(50, $parameters->pageSize);
    }

    public function testGetTableParametersPageSizeFromRequest(): void
    {
        $request = new TableParametersRequest([
            'pageSize' => 40,
        ]);
        $request->cookies->set(self::TABLE_ID, json_encode(['page' => 5, 'pageSize' => 50]));

        $parameters = $request->getTableParameters(self::TABLE_ID);

        self::assertSame(5, $parameters->page);
        self::assertSame(40, $parameters->pageSize);
    }
}
