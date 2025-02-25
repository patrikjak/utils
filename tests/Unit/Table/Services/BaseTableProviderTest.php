<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Unit\Table\Services;

use Patrikjak\Utils\Table\Dto\Table;
use Patrikjak\Utils\Table\Services\TableProviderInterface;
use Patrikjak\Utils\Tests\Integration\Table\Services\Implementations\TableProvider;
use PHPUnit\Framework\TestCase;

class BaseTableProviderTest extends TestCase
{
    private TableProviderInterface $tableProvider;

    public function testCanGetTableInstance(): void
    {
        $table = $this->tableProvider->getTable();

        $this->assertInstanceOf(Table::class, $table);
    }

    public function testCanGetTableId(): void
    {
        $tableId = $this->tableProvider->getTableId();

        $this->assertEquals('table', $tableId);
    }

    public function testCanGetHeader(): void
    {
        $header = $this->tableProvider->getHeader();

        $this->assertIsArray($header);
        $this->assertArrayHasKey('id', $header);
        $this->assertArrayHasKey('name', $header);
        $this->assertArrayHasKey('email', $header);
        $this->assertArrayHasKey('created_at', $header);
        $this->assertArrayHasKey('updated_at', $header);
    }

    public function testCanGetData(): void
    {
        $data = $this->tableProvider->getData();

        $this->assertIsArray($data);
        $this->assertCount(4, $data);
        $this->assertIsArray($data[0]);
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('name', $data[0]);
        $this->assertArrayHasKey('email', $data[0]);
        $this->assertArrayHasKey('created_at', $data[0]);
        $this->assertArrayHasKey('updated_at', $data[0]);
    }

    protected function setUp(): void
    {
        $this->tableProvider = new TableProvider();
    }
}
