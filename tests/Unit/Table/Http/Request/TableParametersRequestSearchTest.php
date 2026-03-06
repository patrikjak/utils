<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Table\Http\Request;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Cookie\CookieJar;
use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class TableParametersRequestSearchTest extends TestCase
{
    private const string TABLE_ID = 'table-id';

    private CookieJar $cookieJar;

    public function testGetTableParametersSearchQueryFromRequest(): void
    {
        $request = new TableParametersRequest(['search' => 'john']);

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertSame('john', $parameters->searchQuery);
    }

    public function testGetTableParametersSearchQueryFromCookie(): void
    {
        $request = new TableParametersRequest(cookies: []);
        $request->cookies->set(
            self::TABLE_ID,
            json_encode([
                'page' => 1,
                'pageSize' => 10,
                'sortCriteria' => null,
                'filterCriteria' => null,
                'searchQuery' => 'alice',
            ]),
        );

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertSame('alice', $parameters->searchQuery);
    }

    public function testGetTableParametersSearchQueryFromRequestOverridesCookie(): void
    {
        $request = new TableParametersRequest(['search' => 'new-term']);
        $request->cookies->set(
            self::TABLE_ID,
            json_encode([
                'page' => 1,
                'pageSize' => 10,
                'sortCriteria' => null,
                'filterCriteria' => null,
                'searchQuery' => 'old-term',
            ]),
        );

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertSame('new-term', $parameters->searchQuery);
    }

    public function testGetTableParametersDeleteSearchClearsQuery(): void
    {
        $request = new TableParametersRequest(['deleteSearch' => 'true']);
        $request->cookies->set(
            self::TABLE_ID,
            json_encode([
                'page' => 1,
                'pageSize' => 10,
                'sortCriteria' => null,
                'filterCriteria' => null,
                'searchQuery' => 'some-query',
            ]),
        );

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertNull($parameters->searchQuery);
    }

    public function testGetTableParametersSearchQueryIsNullByDefault(): void
    {
        $request = new TableParametersRequest();

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertNull($parameters->searchQuery);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSearchQueryIsStoredInCookie(): void
    {
        $request = new TableParametersRequest(['search' => 'stored-query']);

        $request->getTableParameters(self::TABLE_ID);

        $cookie = $this->cookieJar->getQueuedCookies()[0];

        $this->assertNotNull($cookie);
        $this->assertSame(self::TABLE_ID, $cookie->getName());
        $this->assertStringContainsString('"searchQuery":"stored-query"', $cookie->getValue());
    }

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cookieJar = $this->app->make(CookieJar::class);
    }
}
