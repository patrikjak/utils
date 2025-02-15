<?php

namespace Patrikjak\Utils\Tests\Unit\Table\Http\Request;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Cookie\CookieJar;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;
use Orchestra\Testbench\TestCase;

class TableParametersRequestTest extends TestCase
{
    private const string TABLE_ID = 'table-id';

    private CookieJar $cookieJar;

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cookieJar = $this->app->make(CookieJar::class);
    }

    public function testGetTableParametersDefault(): void
    {
        $request = new TableParametersRequest();

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertSame(1, $parameters->page);
        $this->assertSame(10, $parameters->pageSize);
        $this->assertSame([], $this->cookieJar->getQueuedCookies());
    }

    public function testGetTableParametersFromRequest(): void
    {
        $request = new TableParametersRequest([
            'page' => 2,
            'pageSize' => 20,
        ]);

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertSame(2, $parameters->page);
        $this->assertSame(20, $parameters->pageSize);

        $cookie = $this->cookieJar->getQueuedCookies()[0];

        $this->assertNotNull($cookie);
        $this->assertSame(self::TABLE_ID, $cookie->getName());
        $this->assertSame(json_encode(['page' => 2, 'pageSize' => 20, 'sortCriteria' => null]), $cookie->getValue());
    }

    public function testGetTableParametersFromCookie(): void
    {
        $request = new TableParametersRequest(cookies: []);
        $request->cookies->set(self::TABLE_ID, json_encode(['page' => 3, 'pageSize' => 30, 'sortCriteria' => null]));

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertSame(3, $parameters->page);
        $this->assertSame(30, $parameters->pageSize);
    }

    public function testGetTableParametersPageFromRequest(): void
    {
        $request = new TableParametersRequest([
            'page' => 4,
        ]);
        $request->cookies->set(self::TABLE_ID, json_encode(['page' => 5, 'pageSize' => 50, 'sortCriteria' => null]));

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertSame(4, $parameters->page);
        $this->assertSame(50, $parameters->pageSize);

        $cookie = $this->cookieJar->getQueuedCookies()[0];

        $this->assertNotNull($cookie);
        $this->assertSame(self::TABLE_ID, $cookie->getName());
        $this->assertSame(json_encode(['page' => 4, 'pageSize' => 50, 'sortCriteria' => null]), $cookie->getValue());
    }

    public function testGetTableParametersPageSizeFromRequest(): void
    {
        $request = new TableParametersRequest([
            'pageSize' => 40,
        ]);
        $request->cookies->set(self::TABLE_ID, json_encode(['page' => 5, 'pageSize' => 50, 'sortCriteria' => null]));

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertSame(5, $parameters->page);
        $this->assertSame(40, $parameters->pageSize);

        $cookie = $this->cookieJar->getQueuedCookies()[0];

        $this->assertNotNull($cookie);
        $this->assertSame(self::TABLE_ID, $cookie->getName());
        $this->assertSame(json_encode(['page' => 5, 'pageSize' => 40, 'sortCriteria' => null]), $cookie->getValue());
    }
}
