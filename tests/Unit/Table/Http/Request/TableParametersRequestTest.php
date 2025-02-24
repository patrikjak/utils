<?php

namespace Patrikjak\Utils\Tests\Unit\Table\Http\Request;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Cookie\CookieJar;
use Patrikjak\Utils\Common\Dto\Filter\DateFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\NumberFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\SelectFilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\TextFilterCriteria;
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

    public function testGetTableParametersFilterCriteriaFromRequest(): void
    {
        $request = new TableParametersRequest([
            'filter' => [
                'name' => [
                    [
                        'value' => 'John',
                        'type' => 'text',
                        'operator' => 'contains',
                    ],
                ],
                'email' => [
                    [
                        'value' => 'email@example.com',
                        'type' => 'select',
                    ],
                ],
                'created_at' => [
                    [
                        'from' => '2024-12-01',
                        'to' => '2024-12-31',
                        'type' => 'date',
                    ],
                ],
                'random_number' => [
                    [
                        'from' => 10,
                        'type' => 'number',
                    ],
                    [
                        'to' => 10,
                        'type' => 'number',
                    ],
                ],
            ],
        ]);

        $request->cookies->set(
            self::TABLE_ID,
            json_encode([
                'page' => 5,
                'pageSize' => 50,
                'sortCriteria' => null,
                'filterCriteria' => [
                    'location' => [
                        [
                            'value' => 'New York',
                            'type' => 'text',
                            'operator' => 'contains',
                        ],
                    ],
                ],
            ]),
        );

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertSame(5, $parameters->page);
        $this->assertSame(50, $parameters->pageSize);
        $this->assertCount(5, $parameters->filterCriteria->filters);

        $this->assertInstanceOf(TextFilterCriteria::class, $parameters->filterCriteria->filters[0]);
        $this->assertSame('name', $parameters->filterCriteria->filters[0]->column);
        $this->assertSame('John', $parameters->filterCriteria->filters[0]->value);
        $this->assertSame('contains', $parameters->filterCriteria->filters[0]->filterType->value);

        $this->assertSame('email', $parameters->filterCriteria->filters[1]->column);
        $this->assertSame('email@example.com', $parameters->filterCriteria->filters[1]->value);
        $this->assertInstanceOf(SelectFilterCriteria::class, $parameters->filterCriteria->filters[1]);

        $this->assertSame('created_at', $parameters->filterCriteria->filters[2]->column);
        $this->assertInstanceOf(CarbonInterface::class, $parameters->filterCriteria->filters[2]->from);
        $this->assertInstanceOf(CarbonInterface::class, $parameters->filterCriteria->filters[2]->to);
        $this->assertSame('2024-12-01', $parameters->filterCriteria->filters[2]->from->format('Y-m-d'));
        $this->assertSame('2024-12-31', $parameters->filterCriteria->filters[2]->to->format('Y-m-d'));
        $this->assertInstanceOf(DateFilterCriteria::class, $parameters->filterCriteria->filters[2]);

        $this->assertSame('random_number', $parameters->filterCriteria->filters[3]->column);
        $this->assertSame(10.0, $parameters->filterCriteria->filters[3]->from);
        $this->assertNull($parameters->filterCriteria->filters[3]->to);
        $this->assertInstanceOf(NumberFilterCriteria::class, $parameters->filterCriteria->filters[3]);

        $this->assertSame('random_number', $parameters->filterCriteria->filters[4]->column);
        $this->assertNull($parameters->filterCriteria->filters[4]->from);
        $this->assertSame(10.0, $parameters->filterCriteria->filters[4]->to);
        $this->assertInstanceOf(NumberFilterCriteria::class, $parameters->filterCriteria->filters[4]);
    }

    public function testGetTableParametersFilterCriteriaFromCookie(): void
    {
        $request = new TableParametersRequest();
        $request->cookies->set(
            self::TABLE_ID,
            json_encode([
                'page' => 5,
                'pageSize' => 50,
                'sortCriteria' => null,
                'filterCriteria' => [
                    [
                        'column' => 'location',
                        'value' => 'New York',
                        'type' => 'text',
                        'operator' => 'contains',
                    ],
                ],
            ]),
        );

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertSame(5, $parameters->page);
        $this->assertSame(50, $parameters->pageSize);
        $this->assertCount(1, $parameters->filterCriteria->filters);

        $this->assertInstanceOf(TextFilterCriteria::class, $parameters->filterCriteria->filters[0]);
        $this->assertSame('location', $parameters->filterCriteria->filters[0]->column);
        $this->assertSame('New York', $parameters->filterCriteria->filters[0]->value);
        $this->assertSame('contains', $parameters->filterCriteria->filters[0]->filterType->value);
    }

    public function testGetTableParametersFilterCriteriaFromRequestWithInvalidType(): void
    {
        $request = new TableParametersRequest([
            'filter' => [
                'name' => [
                    [
                        'value' => 'John',
                        'type' => 'invalid',
                        'operator' => 'contains',
                    ],
                ],
            ],
        ]);

        $request->cookies->set(
            self::TABLE_ID,
            json_encode([
                'page' => 5,
                'pageSize' => 50,
                'sortCriteria' => null,
                'filterCriteria' => null,
            ]),
        );

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertSame(5, $parameters->page);
        $this->assertSame(50, $parameters->pageSize);
        $this->assertEmpty($parameters->filterCriteria->filters);
    }

    public function testTableParametersWithDeleteFilters(): void
    {
        $request = new TableParametersRequest([
            'deleteFilters' => true,
        ]);

        $request->cookies->set(
            self::TABLE_ID,
            json_encode([
                'page' => 5,
                'pageSize' => 50,
                'sortCriteria' => null,
                'filterCriteria' => [
                    [
                        'column' => 'location',
                        'value' => 'New York',
                        'type' => 'text',
                        'operator' => 'contains',
                    ],
                ],
            ]),
        );

        $parameters = $request->getTableParameters(self::TABLE_ID);

        $this->assertSame(5, $parameters->page);
        $this->assertSame(50, $parameters->pageSize);
        $this->assertNull($parameters->filterCriteria);

        $cookie = $this->cookieJar->getQueuedCookies()[0];

        $this->assertNotNull($cookie);
        $this->assertSame(self::TABLE_ID, $cookie->getName());
        $this->assertSame(
            json_encode(['page' => 5, 'pageSize' => 50, 'sortCriteria' => null, 'filterCriteria' => null]),
            $cookie->getValue(),
        );
    }
}
