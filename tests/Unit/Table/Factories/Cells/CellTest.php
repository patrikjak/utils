<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Unit\Table\Factories\Cells;

use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Cells\Cell as AbstractCell;
use Patrikjak\Utils\Table\Enums\Cells\CellType;
use Patrikjak\Utils\Table\Factories\Cells\CellFactory;
use Patrikjak\Utils\Table\Interfaces\Cells\Cell as CellInterface;
use PHPUnit\Framework\TestCase;

class CellTest extends TestCase
{
    public function testSimpleCellCanBeCreated(): void
    {
        $cell = CellFactory::simple('value');

        $this->assertEquals('value', $cell->value);
        $this->assertNull($cell->icon);
        $this->assertEquals(CellType::SIMPLE, $cell->getType());
        $this->assertInstanceOf(AbstractCell::class, $cell);
        $this->assertInstanceOf(CellInterface::class, $cell);
    }

    public function testSimpleCellWithIconCanBeCreated(): void
    {
        $cell = CellFactory::simple('value with icon', Icon::CHECK);

        $this->assertEquals('value with icon', $cell->value);
        $this->assertEquals(Icon::CHECK, $cell->icon);
        $this->assertEquals(CellType::SIMPLE, $cell->getType());
        $this->assertInstanceOf(AbstractCell::class, $cell);
        $this->assertInstanceOf(CellInterface::class, $cell);
    }

    public function testDoubleCellCanBeCreated(): void
    {
        $cell = CellFactory::double('value', 'addition');

        $this->assertEquals('value', $cell->value);
        $this->assertEquals('addition', $cell->addition);
        $this->assertEquals(CellType::DOUBLE, $cell->getType());
        $this->assertInstanceOf(AbstractCell::class, $cell);
        $this->assertInstanceOf(CellInterface::class, $cell);
    }

    public function testChipCellCanBeCreated(): void
    {
        $cell = CellFactory::chip('value');

        $this->assertEquals('value', $cell->value);
        $this->assertEquals(Type::NEUTRAL, $cell->type);
        $this->assertEquals(CellType::CHIP, $cell->getType());
        $this->assertInstanceOf(AbstractCell::class, $cell);
        $this->assertInstanceOf(CellInterface::class, $cell);
    }
}
