<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Table\Http\Requests;

use Patrikjak\Utils\Common\Dto\Filter\JsonFilterCriteria;
use Patrikjak\Utils\Common\Enums\Filter\JsonFilterType;
use Patrikjak\Utils\Table\Http\Requests\TableParametersRequest;
use Patrikjak\Utils\Tests\Integration\Table\TestCase;

class TableParametersRequestJsonFilterCookieTest extends TestCase
{
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
}
