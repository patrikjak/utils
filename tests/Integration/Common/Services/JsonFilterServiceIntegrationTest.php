<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Common\Services;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Patrikjak\Utils\Common\Dto\Filter\FilterCriteria;
use Patrikjak\Utils\Common\Dto\Filter\JsonFilterCriteria;
use Patrikjak\Utils\Common\Enums\Filter\JsonFilterType;
use Patrikjak\Utils\Common\Services\QueryBuilder\FilterService;
use Patrikjak\Utils\Tests\Integration\TestCase;

class JsonFilterServiceIntegrationTest extends TestCase
{
    private FilterService $filterService;
    private string $testTable = 'json_test_table';

    protected function setUp(): void
    {
        parent::setUp();

        $this->filterService = $this->app->make(FilterService::class);

        $this->createTestTable();
        $this->seedTestData();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists($this->testTable);
        parent::tearDown();
    }

    public function testJsonFilterGeneratesCorrectSqlForContains(): void
    {
        $query = DB::table($this->testTable)->select();

        $this->filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('metadata', 'email', 'john', JsonFilterType::CONTAINS),
        ]));

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $this->assertStringContainsString('JSON_UNQUOTE(JSON_EXTRACT(metadata, ?)) like ?', $sql);
        $this->assertEquals(['$.email', '%john%'], $bindings);
    }

    public function testJsonFilterGeneratesCorrectSqlForEquals(): void
    {
        $query = DB::table($this->testTable)->select();

        $this->filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('data', 'status', 'active', JsonFilterType::EQUALS),
        ]));

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $this->assertStringContainsString('JSON_UNQUOTE(JSON_EXTRACT(data, ?)) = ?', $sql);
        $this->assertEquals(['$.status', 'active'], $bindings);
    }

    public function testJsonFilterGeneratesCorrectSqlForStartsWith(): void
    {
        $query = DB::table($this->testTable)->select();

        $this->filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('metadata', 'phone', '+420', JsonFilterType::STARTS_WITH),
        ]));

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $this->assertStringContainsString('JSON_UNQUOTE(JSON_EXTRACT(metadata, ?)) like ?', $sql);
        $this->assertEquals(['$.phone', '+420%'], $bindings);
    }

    public function testJsonFilterGeneratesCorrectSqlForEndsWith(): void
    {
        $query = DB::table($this->testTable)->select();

        $this->filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('metadata', 'email', '.com', JsonFilterType::ENDS_WITH),
        ]));

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $this->assertStringContainsString('JSON_UNQUOTE(JSON_EXTRACT(metadata, ?)) like ?', $sql);
        $this->assertEquals(['$.email', '%.com'], $bindings);
    }

    public function testJsonFilterGeneratesCorrectSqlForNotContains(): void
    {
        $query = DB::table($this->testTable)->select();

        $this->filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('data', 'status', 'inactive', JsonFilterType::NOT_CONTAINS),
        ]));

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $this->assertStringContainsString('JSON_UNQUOTE(JSON_EXTRACT(data, ?)) not like ?', $sql);
        $this->assertEquals(['$.status', '%inactive%'], $bindings);
    }

    public function testJsonFilterGeneratesCorrectSqlForNotEquals(): void
    {
        $query = DB::table($this->testTable)->select();

        $this->filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('data', 'status', 'active', JsonFilterType::NOT_EQUALS),
        ]));

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $this->assertStringContainsString('JSON_UNQUOTE(JSON_EXTRACT(data, ?)) != ?', $sql);
        $this->assertEquals(['$.status', 'active'], $bindings);
    }

    public function testJsonFilterGeneratesCorrectSqlForNestedPath(): void
    {
        $query = DB::table($this->testTable)->select();

        $this->filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('data', 'address.city', 'Prague', JsonFilterType::EQUALS),
        ]));

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $this->assertStringContainsString('JSON_UNQUOTE(JSON_EXTRACT(data, ?)) = ?', $sql);
        $this->assertEquals(['$.address.city', 'Prague'], $bindings);
    }

    public function testJsonFilterGeneratesCorrectSqlForArrayIndex(): void
    {
        $query = DB::table($this->testTable)->select();

        $this->filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('tags', 'items[0]', 'admin', JsonFilterType::EQUALS),
        ]));

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $this->assertStringContainsString('JSON_UNQUOTE(JSON_EXTRACT(tags, ?)) = ?', $sql);
        $this->assertEquals(['$.items[0]', 'admin'], $bindings);
    }

    public function testJsonFilterGeneratesCorrectSqlForComplexArrayPath(): void
    {
        $query = DB::table($this->testTable)->select();

        $this->filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('contacts', 'phones[1]', '+420987654', JsonFilterType::CONTAINS),
        ]));

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $this->assertStringContainsString('JSON_UNQUOTE(JSON_EXTRACT(contacts, ?)) like ?', $sql);
        $this->assertEquals(['$.phones[1]', '%+420987654%'], $bindings);
    }

    public function testJsonFilterGeneratesCorrectSqlForRootPath(): void
    {
        $query = DB::table($this->testTable)->select();

        $this->filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('settings', null, 'dark', JsonFilterType::CONTAINS),
        ]));

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $this->assertStringContainsString('JSON_UNQUOTE(JSON_EXTRACT(settings, ?)) like ?', $sql);
        $this->assertEquals(['$', '%dark%'], $bindings);
    }

    public function testJsonFilterGeneratesCorrectSqlForEmptyPath(): void
    {
        $query = DB::table($this->testTable)->select();

        $this->filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('settings', '', 'light', JsonFilterType::CONTAINS),
        ]));

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $this->assertStringContainsString('JSON_UNQUOTE(JSON_EXTRACT(settings, ?)) like ?', $sql);
        $this->assertEquals(['$', '%light%'], $bindings);
    }

    public function testJsonFilterGeneratesCorrectSqlForMultipleFilters(): void
    {
        $query = DB::table($this->testTable)->select();

        $this->filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('metadata', 'email', 'john', JsonFilterType::CONTAINS),
            new JsonFilterCriteria('data', 'status', 'active', JsonFilterType::EQUALS),
        ]));

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $this->assertStringContainsString('JSON_UNQUOTE(JSON_EXTRACT(metadata, ?)) like ?', $sql);
        $this->assertStringContainsString('JSON_UNQUOTE(JSON_EXTRACT(data, ?)) = ?', $sql);
        $this->assertStringContainsString('and', $sql);

        $this->assertEquals(['$.email', '%john%', '$.status', 'active'], $bindings);
    }

    public function testJsonFilterGeneratesCorrectSqlWithColumnMask(): void
    {
        $query = DB::table($this->testTable)->select();

        $columnMask = [
            'json_test_table.metadata' => 'user_metadata',
        ];

        $this->filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('user_metadata', 'email', 'admin', JsonFilterType::CONTAINS),
        ]), $columnMask);

        $sql = $query->toSql();

        $this->assertStringContainsString('JSON_UNQUOTE(JSON_EXTRACT(json_test_table.metadata', $sql);
        $this->assertStringNotContainsString('user_metadata', $sql);
    }

    public function testJsonFilterAllOperatorTypes(): void
    {
        $query = DB::table($this->testTable)->select();

        $this->filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('test1', 'path', 'val1', JsonFilterType::CONTAINS),
            new JsonFilterCriteria('test2', 'path', 'val2', JsonFilterType::NOT_CONTAINS),
            new JsonFilterCriteria('test3', 'path', 'val3', JsonFilterType::EQUALS),
            new JsonFilterCriteria('test4', 'path', 'val4', JsonFilterType::NOT_EQUALS),
            new JsonFilterCriteria('test5', 'path', 'val5', JsonFilterType::STARTS_WITH),
            new JsonFilterCriteria('test6', 'path', 'val6', JsonFilterType::ENDS_WITH),
        ]));

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $this->assertStringContainsString('like ?', $sql);
        $this->assertStringContainsString('not like ?', $sql);
        $this->assertStringContainsString('= ?', $sql);
        $this->assertStringContainsString('!= ?', $sql);

        $this->assertEquals([
            '$.path', '%val1%',
            '$.path', '%val2%',
            '$.path', 'val3',
            '$.path', 'val4',
            '$.path', 'val5%',
            '$.path', '%val6',
        ], $bindings);
    }

    public function testJsonFilterValidatesPathFormatting(): void
    {
        $query = DB::table($this->testTable)->select();

        $this->filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('col1', 'simple', 'val', JsonFilterType::EQUALS),
            new JsonFilterCriteria('col2', '$.already.prefixed', 'val', JsonFilterType::EQUALS),
            new JsonFilterCriteria('col3', 'nested.path', 'val', JsonFilterType::EQUALS),
            new JsonFilterCriteria('col4', 'array[0]', 'val', JsonFilterType::EQUALS),
            new JsonFilterCriteria('col5', 'complex[0].path[1]', 'val', JsonFilterType::EQUALS),
        ]));

        $bindings = $query->getBindings();

        $this->assertContains('$.simple', $bindings);
        $this->assertContains('$.already.prefixed', $bindings);
        $this->assertContains('$.nested.path', $bindings);
        $this->assertContains('$.array[0]', $bindings);
        $this->assertContains('$.complex[0].path[1]', $bindings);
    }

    public function testJsonFilterSkipsEmptyValue(): void
    {
        $query = DB::table($this->testTable)->select();

        $this->filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('metadata', 'email', '', JsonFilterType::CONTAINS),
        ]));

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $this->assertStringNotContainsString('JSON_UNQUOTE', $sql);
        $this->assertEmpty($bindings);
    }

    public function testJsonFilterSkipsNullValue(): void
    {
        $query = DB::table($this->testTable)->select();

        $this->filterService->applyFilter($query, new FilterCriteria([
            new JsonFilterCriteria('metadata', 'email', null, JsonFilterType::CONTAINS),
        ]));

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        $this->assertStringNotContainsString('JSON_UNQUOTE', $sql);
        $this->assertEmpty($bindings);
    }

    private function createTestTable(): void
    {
        Schema::create($this->testTable, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('metadata');
            $table->json('data');
            $table->json('tags');
            $table->json('contacts');
            $table->json('settings');
            $table->timestamps();
        });
    }

    private function seedTestData(): void
    {
        DB::table($this->testTable)->insert([
            [
                'id' => 1,
                'name' => 'John Doe',
                'metadata' => json_encode([
                    'email' => 'john@example.com',
                    'phone' => '+420123456789',
                ]),
                'data' => json_encode([
                    'address' => [
                        'city' => 'Prague',
                        'country' => 'CZ',
                    ],
                    'status' => 'active',
                ]),
                'tags' => json_encode([
                    'items' => ['user', 'customer', 'premium'],
                ]),
                'contacts' => json_encode([
                    'phones' => ['+420123456', '+420987654'],
                ]),
                'settings' => json_encode([
                    'theme' => 'dark',
                    'notifications' => true,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
