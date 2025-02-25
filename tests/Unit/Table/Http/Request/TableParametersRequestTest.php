<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Unit\Table\Http\Request;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Cookie\CookieJar;
use Orchestra\Testbench\TestCase;
use Patrikjak\Utils\Common\Dto\Filter\DateFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\NumberFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\SelectFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\TextFilterCriteria;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;

class TableParametersRequestTest extends TestCase
{
    private const string TABLE_ID = 'table-id';

    private CookieJar $cookieJar;

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
        $this->assertSame(
            json_encode(['page' => 2, 'pageSize' => 20, 'sortCriteria' => null, 'filterCriteria' => null]),
            $cookie->getValue(),
        );
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
        $this->assertSame(
            json_encode(['page' => 4, 'pageSize' => 50, 'sortCriteria' => null, 'filterCriteria' => null]),
            $cookie->getValue(),
        );
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
        $this->assertSame(
            json_encode(['page' => 5, 'pageSize' => 40, 'sortCriteria' => null, 'filterCriteria' => null]),
            $cookie->getValue(),
        );
    }

    public function testGetTableParametersSortCriteriaFromRequest(): void
    {
        $request = new TableParametersRequest([
            'sort' => 'name',
            'order' => 'desc',
        ]);
        $request->cookies->set(
            self::TABLE_ID,
            json_encode([
                'page' => 5,
                'pageSize' => 50,
                'sortCriteria' => ['column' => 'location', 'order' => 'asc'],
            ]),
        );

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertSame(5, $parameters->page);
        $this->assertSame(50, $parameters->pageSize);
        $this->assertSame('name', $parameters->sortCriteria->column);
        $this->assertSame('desc', $parameters->sortCriteria->order->value);

        $cookie = $this->cookieJar->getQueuedCookies()[0];

        $this->assertNotNull($cookie);
        $this->assertSame(self::TABLE_ID, $cookie->getName());
        $this->assertSame(
            json_encode(
                [
                    'page' => 5,
                    'pageSize' => 50,
                    'sortCriteria' => ['column' => 'name', 'order' => 'desc'],
                    'filterCriteria' => null,
                ],
            ),
            $cookie->getValue(),
        );
    }

    public function testGetTableParametersSortCriteriaFromCookie(): void
    {
        $request = new TableParametersRequest();
        $request->cookies->set(
            self::TABLE_ID,
            json_encode([
                'page' => 5,
                'pageSize' => 50,
                'sortCriteria' => ['column' => 'location', 'order' => 'asc'],
            ]),
        );

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertSame(5, $parameters->page);
        $this->assertSame(50, $parameters->pageSize);
        $this->assertSame('location', $parameters->sortCriteria->column);
        $this->assertSame('asc', $parameters->sortCriteria->order->value);
    }

    public function testGetTableParametersWithDeleteSortCriteria(): void
    {
        $request = new TableParametersRequest([
            'sort' => 'name',
            'order' => 'desc',
            'deleteSort' => true,
        ]);

        $request->cookies->set(
            self::TABLE_ID,
            json_encode([
                'page' => 5,
                'pageSize' => 50,
                'sortCriteria' => ['column' => 'location', 'order' => 'asc'],
            ]),
        );

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertSame(5, $parameters->page);
        $this->assertSame(50, $parameters->pageSize);
        $this->assertNull($parameters->sortCriteria);

        $cookie = $this->cookieJar->getQueuedCookies()[0];

        $this->assertNotNull($cookie);
        $this->assertSame(self::TABLE_ID, $cookie->getName());
        $this->assertSame(
            json_encode(['page' => 5, 'pageSize' => 50, 'sortCriteria' => null, 'filterCriteria' => null]),
            $cookie->getValue(),
        );
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
