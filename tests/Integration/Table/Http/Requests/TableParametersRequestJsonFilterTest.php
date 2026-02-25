<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Table\Http\Requests;

use Illuminate\Http\Request;
use Patrikjak\Utils\Common\Dto\Filter\JsonFilterCriteria;
use Patrikjak\Utils\Common\Enums\Filter\JsonFilterType;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;
use Patrikjak\Utils\Tests\Integration\Table\TestCase;

class TableParametersRequestJsonFilterTest extends TestCase
{
    public function testCanParseSimpleJsonFilter(): void
    {
        $request = $this->createRequestWithJsonFilter([
            'metadata' => [
                [
                    'type' => 'json',
                    'operator' => 'contains',
                    'jsonPath' => 'email',
                    'value' => 'john@example.com',
                ],
            ],
        ]);

        $parameters = $request->getTableParameters('test-table');

        $this->assertNotNull($parameters->filterCriteria);
        $this->assertCount(1, $parameters->filterCriteria->filters);

        $filter = $parameters->filterCriteria->filters[0];
        $this->assertInstanceOf(JsonFilterCriteria::class, $filter);
        $this->assertEquals('metadata', $filter->column);
        $this->assertEquals('email', $filter->jsonPath);
        $this->assertEquals('john@example.com', $filter->value);
        $this->assertEquals(JsonFilterType::CONTAINS, $filter->filterType);
    }

    public function testCanParseNestedJsonPathFilter(): void
    {
        $request = $this->createRequestWithJsonFilter([
            'data' => [
                [
                    'type' => 'json',
                    'operator' => 'equals',
                    'jsonPath' => 'user.address.city',
                    'value' => 'Prague',
                ],
            ],
        ]);

        $parameters = $request->getTableParameters('test-table');

        $filter = $parameters->filterCriteria->filters[0];
        $this->assertInstanceOf(JsonFilterCriteria::class, $filter);
        $this->assertEquals('data', $filter->column);
        $this->assertEquals('user.address.city', $filter->jsonPath);
        $this->assertEquals('Prague', $filter->value);
        $this->assertEquals(JsonFilterType::EQUALS, $filter->filterType);
    }

    public function testCanParseArrayIndexJsonFilter(): void
    {
        $request = $this->createRequestWithJsonFilter([
            'tags' => [
                [
                    'type' => 'json',
                    'operator' => 'starts_with',
                    'jsonPath' => 'items[0]',
                    'value' => 'tech',
                ],
            ],
        ]);

        $parameters = $request->getTableParameters('test-table');

        $filter = $parameters->filterCriteria->filters[0];
        $this->assertInstanceOf(JsonFilterCriteria::class, $filter);
        $this->assertEquals('tags', $filter->column);
        $this->assertEquals('items[0]', $filter->jsonPath);
        $this->assertEquals('tech', $filter->value);
        $this->assertEquals(JsonFilterType::STARTS_WITH, $filter->filterType);
    }

    public function testCanParseComplexArrayPathFilter(): void
    {
        $request = $this->createRequestWithJsonFilter([
            'contacts' => [
                [
                    'type' => 'json',
                    'operator' => 'ends_with',
                    'jsonPath' => 'users[0].phones[1]',
                    'value' => '789',
                ],
            ],
        ]);

        $parameters = $request->getTableParameters('test-table');

        $filter = $parameters->filterCriteria->filters[0];
        $this->assertInstanceOf(JsonFilterCriteria::class, $filter);
        $this->assertEquals('contacts', $filter->column);
        $this->assertEquals('users[0].phones[1]', $filter->jsonPath);
        $this->assertEquals('789', $filter->value);
        $this->assertEquals(JsonFilterType::ENDS_WITH, $filter->filterType);
    }

    public function testCanParseRootJsonFilter(): void
    {
        $request = $this->createRequestWithJsonFilter([
            'settings' => [
                [
                    'type' => 'json',
                    'operator' => 'contains',
                    'jsonPath' => null,
                    'value' => 'search_term',
                ],
            ],
        ]);

        $parameters = $request->getTableParameters('test-table');

        $filter = $parameters->filterCriteria->filters[0];
        $this->assertInstanceOf(JsonFilterCriteria::class, $filter);
        $this->assertEquals('settings', $filter->column);
        $this->assertNull($filter->jsonPath);
        $this->assertEquals('search_term', $filter->value);
        $this->assertEquals(JsonFilterType::CONTAINS, $filter->filterType);
    }

    public function testCanParseEmptyJsonPathFilter(): void
    {
        $request = $this->createRequestWithJsonFilter([
            'preferences' => [
                [
                    'type' => 'json',
                    'operator' => 'not_contains',
                    'jsonPath' => '',
                    'value' => 'unwanted',
                ],
            ],
        ]);

        $parameters = $request->getTableParameters('test-table');

        $filter = $parameters->filterCriteria->filters[0];
        $this->assertInstanceOf(JsonFilterCriteria::class, $filter);
        $this->assertEquals('preferences', $filter->column);
        $this->assertEquals('', $filter->jsonPath);
        $this->assertEquals('unwanted', $filter->value);
        $this->assertEquals(JsonFilterType::NOT_CONTAINS, $filter->filterType);
    }

    public function testCanParseMultipleJsonFilters(): void
    {
        $request = $this->createRequestWithJsonFilter([
            'metadata' => [
                [
                    'type' => 'json',
                    'operator' => 'contains',
                    'jsonPath' => 'email',
                    'value' => 'john',
                ],
                [
                    'type' => 'json',
                    'operator' => 'starts_with',
                    'jsonPath' => 'phone',
                    'value' => '+420',
                ],
            ],
            'data' => [
                [
                    'type' => 'json',
                    'operator' => 'equals',
                    'jsonPath' => 'status',
                    'value' => 'active',
                ],
            ],
        ]);

        $parameters = $request->getTableParameters('test-table');

        $this->assertCount(3, $parameters->filterCriteria->filters);

        // First filter
        $filter1 = $parameters->filterCriteria->filters[0];
        $this->assertEquals('metadata', $filter1->column);
        $this->assertEquals('email', $filter1->jsonPath);
        $this->assertEquals('john', $filter1->value);

        // Second filter
        $filter2 = $parameters->filterCriteria->filters[1];
        $this->assertEquals('metadata', $filter2->column);
        $this->assertEquals('phone', $filter2->jsonPath);
        $this->assertEquals('+420', $filter2->value);

        // Third filter
        $filter3 = $parameters->filterCriteria->filters[2];
        $this->assertEquals('data', $filter3->column);
        $this->assertEquals('status', $filter3->jsonPath);
        $this->assertEquals('active', $filter3->value);
    }

    public function testIgnoresInvalidJsonFilterOperator(): void
    {
        $request = $this->createRequestWithJsonFilter([
            'metadata' => [
                [
                    'type' => 'json',
                    'operator' => 'invalid_operator',
                    'jsonPath' => 'email',
                    'value' => 'test',
                ],
            ],
        ]);

        $parameters = $request->getTableParameters('test-table');

        $this->assertNotNull($parameters->filterCriteria);
        $this->assertEmpty($parameters->filterCriteria->filters);
    }

    public function testIgnoresJsonFilterWithoutOperator(): void
    {
        $request = $this->createRequestWithJsonFilter([
            'metadata' => [
                [
                    'type' => 'json',
                    'jsonPath' => 'email',
                    'value' => 'test',
                    // Missing 'operator'
                ],
            ],
        ]);

        $parameters = $request->getTableParameters('test-table');

        $this->assertNotNull($parameters->filterCriteria);
        $this->assertEmpty($parameters->filterCriteria->filters);
    }

    public function testAllJsonFilterTypes(): void
    {
        $request = $this->createRequestWithJsonFilter([
            'test1' => [['type' => 'json', 'operator' => 'contains', 'jsonPath' => 'path', 'value' => 'val']],
            'test2' => [['type' => 'json', 'operator' => 'not_contains', 'jsonPath' => 'path', 'value' => 'val']],
            'test3' => [['type' => 'json', 'operator' => 'equals', 'jsonPath' => 'path', 'value' => 'val']],
            'test4' => [['type' => 'json', 'operator' => 'not_equals', 'jsonPath' => 'path', 'value' => 'val']],
            'test5' => [['type' => 'json', 'operator' => 'starts_with', 'jsonPath' => 'path', 'value' => 'val']],
            'test6' => [['type' => 'json', 'operator' => 'ends_with', 'jsonPath' => 'path', 'value' => 'val']],
        ]);

        $parameters = $request->getTableParameters('test-table');

        $this->assertCount(6, $parameters->filterCriteria->filters);

        $expectedTypes = [
            JsonFilterType::CONTAINS,
            JsonFilterType::NOT_CONTAINS,
            JsonFilterType::EQUALS,
            JsonFilterType::NOT_EQUALS,
            JsonFilterType::STARTS_WITH,
            JsonFilterType::ENDS_WITH,
        ];

        foreach ($parameters->filterCriteria->filters as $index => $filter) {
            $this->assertInstanceOf(JsonFilterCriteria::class, $filter);
            $this->assertEquals($expectedTypes[$index], $filter->filterType);
        }
    }

    public function testIgnoresJsonFilterWithoutValue(): void
    {
        $request = $this->createRequestWithJsonFilter([
            'metadata' => [
                [
                    'type' => 'json',
                    'operator' => 'contains',
                    'jsonPath' => 'email',
                    // Missing 'value'
                ],
            ],
        ]);

        $parameters = $request->getTableParameters('test-table');

        $this->assertNotNull($parameters->filterCriteria);
        $this->assertEmpty($parameters->filterCriteria->filters);
    }

    public function testCanParseJsonFilterFromCookie(): void
    {
        $request = new TableParametersRequest();
        $request->cookies->set('test-table', json_encode([
            'page' => 1,
            'pageSize' => 10,
            'sortCriteria' => null,
            'filterCriteria' => [
                [
                    'column' => 'metadata',
                    'type' => 'json',
                    'operator' => 'contains',
                    'jsonPath' => 'email',
                    'value' => 'john@example.com',
                ],
            ],
        ]));

        $parameters = $request->getTableParameters('test-table');

        $this->assertNotNull($parameters->filterCriteria);
        $this->assertCount(1, $parameters->filterCriteria->filters);

        $filter = $parameters->filterCriteria->filters[0];
        $this->assertInstanceOf(JsonFilterCriteria::class, $filter);
        $this->assertEquals('metadata', $filter->column);
        $this->assertEquals('email', $filter->jsonPath);
        $this->assertEquals('john@example.com', $filter->value);
        $this->assertEquals(JsonFilterType::CONTAINS, $filter->filterType);
    }

    public function testCanParseMultipleJsonFiltersFromCookie(): void
    {
        $request = new TableParametersRequest();
        $request->cookies->set('test-table', json_encode([
            'page' => 1,
            'pageSize' => 10,
            'sortCriteria' => null,
            'filterCriteria' => [
                [
                    'column' => 'metadata',
                    'type' => 'json',
                    'operator' => 'equals',
                    'jsonPath' => 'status',
                    'value' => 'active',
                ],
                [
                    'column' => 'settings',
                    'type' => 'json',
                    'operator' => 'not_equals',
                    'jsonPath' => 'theme',
                    'value' => 'light',
                ],
            ],
        ]));

        $parameters = $request->getTableParameters('test-table');

        $this->assertCount(2, $parameters->filterCriteria->filters);

        $filter1 = $parameters->filterCriteria->filters[0];
        $this->assertInstanceOf(JsonFilterCriteria::class, $filter1);
        $this->assertEquals('metadata', $filter1->column);
        $this->assertEquals('status', $filter1->jsonPath);
        $this->assertEquals('active', $filter1->value);
        $this->assertEquals(JsonFilterType::EQUALS, $filter1->filterType);

        $filter2 = $parameters->filterCriteria->filters[1];
        $this->assertInstanceOf(JsonFilterCriteria::class, $filter2);
        $this->assertEquals('settings', $filter2->column);
        $this->assertEquals('theme', $filter2->jsonPath);
        $this->assertEquals('light', $filter2->value);
        $this->assertEquals(JsonFilterType::NOT_EQUALS, $filter2->filterType);
    }

    public function testIgnoresInvalidJsonFilterOperatorFromCookie(): void
    {
        $request = new TableParametersRequest();
        $request->cookies->set('test-table', json_encode([
            'page' => 1,
            'pageSize' => 10,
            'sortCriteria' => null,
            'filterCriteria' => [
                [
                    'column' => 'metadata',
                    'type' => 'json',
                    'operator' => 'invalid_operator',
                    'jsonPath' => 'email',
                    'value' => 'test',
                ],
            ],
        ]));

        $parameters = $request->getTableParameters('test-table');

        $this->assertNotNull($parameters->filterCriteria);
        $this->assertEmpty($parameters->filterCriteria->filters);
    }

    /**
     * @param array<string, array<array<string, string>>> $filterData
     */
    private function createRequestWithJsonFilter(array $filterData): TableParametersRequest
    {
        $request = Request::create('/test', 'GET', [
            'filter' => $filterData,
        ]);

        return TableParametersRequest::createFrom($request);
    }
}
