<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Table\Dto;

use Patrikjak\Utils\Table\ValueObjects\ColumnVisibility;
use PHPUnit\Framework\TestCase;

class ColumnVisibilityTest extends TestCase
{
    private const array COLUMNS = [
        'name' => 'Name',
        'email' => 'Email',
        'role' => 'Role',
        'status' => 'Status',
    ];

    public function testReturnsAllColumnsWhenNoneAreHiddenByDefault(): void
    {
        $visibility = new ColumnVisibility(self::COLUMNS);

        $result = $visibility->getVisibleColumns(null);

        $this->assertSame(['name', 'email', 'role', 'status'], $result);
    }

    public function testRespectsDefaultHiddenColumns(): void
    {
        $visibility = new ColumnVisibility(self::COLUMNS, ['role', 'status']);

        $result = $visibility->getVisibleColumns(null);

        $this->assertSame(['name', 'email'], $result);
    }

    public function testReturnsRequestedVisibleColumns(): void
    {
        $visibility = new ColumnVisibility(self::COLUMNS);

        $result = $visibility->getVisibleColumns(['email', 'status']);

        $this->assertSame(['email', 'status'], $result);
    }

    public function testIgnoresRequestedColumnsThatAreNotInDefinedColumns(): void
    {
        $visibility = new ColumnVisibility(self::COLUMNS);

        $result = $visibility->getVisibleColumns(['email', 'nonexistent']);

        $this->assertSame(['email'], $result);
    }

    public function testFallsBackToFirstColumnWhenAllDefaultHiddenMatchAllColumns(): void
    {
        $visibility = new ColumnVisibility(self::COLUMNS, ['name', 'email', 'role', 'status']);

        $result = $visibility->getVisibleColumns(null);

        $this->assertSame(['name'], $result);
    }

    public function testFallsBackToFirstColumnWhenRequestedColumnsAreAllInvalid(): void
    {
        $visibility = new ColumnVisibility(self::COLUMNS);

        $result = $visibility->getVisibleColumns(['nonexistent', 'alsonotreal']);

        $this->assertSame(['name'], $result);
    }

    public function testPreservesOrderOfDefinedColumnsNotRequestedOrder(): void
    {
        $visibility = new ColumnVisibility(self::COLUMNS);

        $result = $visibility->getVisibleColumns(['status', 'name']);

        $this->assertSame(['name', 'status'], $result);
    }
}
