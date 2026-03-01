<?php

declare(strict_types=1);

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
        $this->assertSame('metadata', $filter->column);
        $this->assertSame('email', $filter->jsonPath);
        $this->assertSame('john@example.com', $filter->value);
        $this->assertSame(JsonFilterType::CONTAINS, $filter->filterType);
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
        $this->assertSame('data', $filter->column);
        $this->assertSame('user.address.city', $filter->jsonPath);
        $this->assertSame('Prague', $filter->value);
        $this->assertSame(JsonFilterType::EQUALS, $filter->filterType);
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
        $this->assertSame('tags', $filter->column);
        $this->assertSame('items[0]', $filter->jsonPath);
        $this->assertSame('tech', $filter->value);
        $this->assertSame(JsonFilterType::STARTS_WITH, $filter->filterType);
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
        $this->assertSame('contacts', $filter->column);
        $this->assertSame('users[0].phones[1]', $filter->jsonPath);
        $this->assertSame('789', $filter->value);
        $this->assertSame(JsonFilterType::ENDS_WITH, $filter->filterType);
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
        $this->assertSame('settings', $filter->column);
        $this->assertNull($filter->jsonPath);
        $this->assertSame('search_term', $filter->value);
        $this->assertSame(JsonFilterType::CONTAINS, $filter->filterType);
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
        $this->assertSame('preferences', $filter->column);
        $this->assertNull($filter->jsonPath);
        $this->assertSame('unwanted', $filter->value);
        $this->assertSame(JsonFilterType::NOT_CONTAINS, $filter->filterType);
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

        $filter1 = $parameters->filterCriteria->filters[0];
        $this->assertSame('metadata', $filter1->column);
        $this->assertSame('email', $filter1->jsonPath);
        $this->assertSame('john', $filter1->value);

        $filter2 = $parameters->filterCriteria->filters[1];
        $this->assertSame('metadata', $filter2->column);
        $this->assertSame('phone', $filter2->jsonPath);
        $this->assertSame('+420', $filter2->value);

        $filter3 = $parameters->filterCriteria->filters[2];
        $this->assertSame('data', $filter3->column);
        $this->assertSame('status', $filter3->jsonPath);
        $this->assertSame('active', $filter3->value);
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
            $this->assertSame($expectedTypes[$index], $filter->filterType);
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
                ],
            ],
        ]);

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
