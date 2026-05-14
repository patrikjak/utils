<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Table\Registry;

use Patrikjak\Utils\Table\Exceptions\UnregisteredCellTypeException;
use Patrikjak\Utils\Table\Registry\CellRegistry;
use PHPUnit\Framework\TestCase;

final class CellRegistryTest extends TestCase
{
    private CellRegistry $registry;

    protected function setUp(): void
    {
        $this->registry = new CellRegistry();
    }

    public function testRegisteredViewCanBeRetrieved(): void
    {
        $this->registry->register('simple', 'pjutils.table::cells.simple');

        $this->assertSame('pjutils.table::cells.simple', $this->registry->getView('simple'));
    }

    public function testMultipleTypesCanBeRegistered(): void
    {
        $this->registry->register('simple', 'pjutils.table::cells.simple');
        $this->registry->register('chip', 'pjutils.table::cells.chip');

        $this->assertSame('pjutils.table::cells.simple', $this->registry->getView('simple'));
        $this->assertSame('pjutils.table::cells.chip', $this->registry->getView('chip'));
    }

    public function testRegisterOverwritesExistingType(): void
    {
        $this->registry->register('simple', 'pjutils.table::cells.simple');
        $this->registry->register('simple', 'app.table.cells.custom-simple');

        $this->assertSame('app.table.cells.custom-simple', $this->registry->getView('simple'));
    }

    public function testGetViewThrowsForUnregisteredType(): void
    {
        $this->expectException(UnregisteredCellTypeException::class);
        $this->expectExceptionMessage('No cell view registered for type "money".');

        $this->registry->getView('money');
    }
}
