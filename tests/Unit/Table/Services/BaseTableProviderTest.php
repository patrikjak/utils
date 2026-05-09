<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Table\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Patrikjak\Utils\Table\Dto\Table;
use Patrikjak\Utils\Table\Services\TableProvider;
use Patrikjak\Utils\Tests\Integration\Table\Services\Implementations\MinimalTableProvider as TableProviderImpl;
use Patrikjak\Utils\Tests\Integration\TestCase;

final class BaseTableProviderTest extends TestCase
{
    private TableProvider $tableProvider;

    /**
     * @throws BindingResolutionException
     */
    public function testCanGetTableInstance(): void
    {
        $table = $this->tableProvider->getTable();

        $this->assertInstanceOf(Table::class, $table);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableHasExpectedHeader(): void
    {
        $table = $this->tableProvider->getTable();

        $this->assertArrayHasKey('id', $table->header);
        $this->assertArrayHasKey('name', $table->header);
        $this->assertArrayHasKey('email', $table->header);
        $this->assertArrayHasKey('created_at', $table->header);
        $this->assertArrayHasKey('updated_at', $table->header);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableHasExpectedData(): void
    {
        $table = $this->tableProvider->getTable();

        $this->assertIsArray($table->data);
        $this->assertCount(4, $table->data);
        $this->assertIsArray($table->data[0]);
        $this->assertArrayHasKey('id', $table->data[0]);
        $this->assertArrayHasKey('name', $table->data[0]);
        $this->assertArrayHasKey('email', $table->data[0]);
        $this->assertArrayHasKey('created_at', $table->data[0]);
        $this->assertArrayHasKey('updated_at', $table->data[0]);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableHasExpectedTableId(): void
    {
        $table = $this->tableProvider->getTable();

        $this->assertSame('table', $table->tableId);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tableProvider = new TableProviderImpl();
    }
}
