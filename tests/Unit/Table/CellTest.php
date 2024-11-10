<?php

namespace Patrikjak\Utils\Tests\Unit\Table;

use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Cells\Cell as AbstractCell;
use Patrikjak\Utils\Table\Enums\CellType;
use Patrikjak\Utils\Table\Interfaces\Cells\Cell as CellInterface;
use Patrikjak\Utils\Table\Services\Cell;
use PHPUnit\Framework\TestCase;

class CellTest extends TestCase
{
    public function testSimpleCellCanBeCreated(): void
    {
        $cell = Cell::simple('value');

        $this->assertEquals('value', $cell->value);
        $this->assertNull($cell->icon);
        $this->assertEquals(CellType::SIMPLE, $cell->getType());
        $this->assertInstanceOf(AbstractCell::class, $cell);
        $this->assertInstanceOf(CellInterface::class, $cell);
    }

    public function testSimpleCellWithIconCanBeCreated(): void
    {
        $cell = Cell::simple('value with icon', Icon::CHECK);

        $this->assertEquals('value with icon', $cell->value);
        $this->assertEquals(Icon::CHECK, $cell->getIcon());
        $this->assertEquals(CellType::SIMPLE, $cell->getType());
        $this->assertInstanceOf(AbstractCell::class, $cell);
        $this->assertInstanceOf(CellInterface::class, $cell);
    }

    public function testDoubleCellCanBeCreated(): void
    {
        $cell = Cell::double('value', 'addition');

        $this->assertEquals('value', $cell->value);
        $this->assertEquals('addition', $cell->addition);
        $this->assertEquals(CellType::DOUBLE, $cell->getType());
        $this->assertInstanceOf(AbstractCell::class, $cell);
        $this->assertInstanceOf(CellInterface::class, $cell);
    }

    public function testChipCellCanBeCreated(): void
    {
        $cell = Cell::chip('value');

        $this->assertEquals('value', $cell->value);
        $this->assertEquals(Type::NEUTRAL, $cell->type);
        $this->assertEquals(CellType::CHIP, $cell->getType());
        $this->assertInstanceOf(AbstractCell::class, $cell);
        $this->assertInstanceOf(CellInterface::class, $cell);
    }
}
