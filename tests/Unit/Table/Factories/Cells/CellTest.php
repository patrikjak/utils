<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Table\Factories\Cells;

use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Common\Icon;
use Patrikjak\Utils\Table\Builder\Cell as CellFactory;
use PHPUnit\Framework\TestCase;

final class CellTest extends TestCase
{
    public function testSimpleCellCanBeCreated(): void
    {
        $cell = CellFactory::simple('value');

        $this->assertEquals('value', $cell->value);
        $this->assertNull($cell->icon);
        $this->assertEquals('simple', $cell->getType());
    }

    public function testSimpleCellWithIconCanBeCreated(): void
    {
        $icon = Icon::heroicon('heroicon-o-check');
        $cell = CellFactory::simple('value with icon', $icon);

        $this->assertEquals('value with icon', $cell->value);
        $this->assertSame($icon, $cell->icon);
        $this->assertEquals('simple', $cell->getType());
    }

    public function testTwoLineCellCanBeCreated(): void
    {
        $cell = CellFactory::twoLine('value', 'addition');

        $this->assertEquals('value', $cell->value);
        $this->assertEquals('addition', $cell->addition);
        $this->assertEquals('two-line', $cell->getType());
    }

    public function testChipCellCanBeCreated(): void
    {
        $cell = CellFactory::chip('value');

        $this->assertEquals('value', $cell->value);
        $this->assertEquals(Type::NEUTRAL, $cell->type);
        $this->assertEquals('chip', $cell->getType());
    }

    public function testLinkCellCanBeCreated(): void
    {
        $cell = CellFactory::link('value', 'https://example.com');

        $this->assertEquals('value', $cell->value);
        $this->assertEquals('https://example.com', $cell->href);
        $this->assertEquals('link', $cell->getType());
    }

    public function testSimpleCellMaxLengthIsStoredCorrectly(): void
    {
        $cell = CellFactory::simple('value', maxLength: 3);

        $this->assertEquals(3, $cell->maxLength);
    }

    public function testSimpleCellWithoutMaxLengthHasNullMaxLength(): void
    {
        $cell = CellFactory::simple('value');

        $this->assertNull($cell->maxLength);
    }

    public function testTwoLineCellMaxLengthIsStoredCorrectly(): void
    {
        $cell = CellFactory::twoLine('value', 'addition', maxLength: 3);

        $this->assertEquals(3, $cell->maxLength);
    }

    public function testChipCellMaxLengthIsStoredCorrectly(): void
    {
        $cell = CellFactory::chip('value', maxLength: 3);

        $this->assertEquals(3, $cell->maxLength);
    }

    public function testLinkCellMaxLengthIsStoredCorrectly(): void
    {
        $cell = CellFactory::link('value', 'https://example.com', maxLength: 3);

        $this->assertEquals(3, $cell->maxLength);
    }

    public function testSimpleCellNoTruncationDefaultsToFalse(): void
    {
        $cell = CellFactory::simple('value');

        $this->assertFalse($cell->noTruncation);
    }

    public function testSimpleCellNoTruncationCanBeSetToTrue(): void
    {
        $cell = CellFactory::simple('value', noTruncation: true);

        $this->assertTrue($cell->noTruncation);
    }

    public function testTwoLineCellNoTruncationCanBeSetToTrue(): void
    {
        $cell = CellFactory::twoLine('value', 'addition', noTruncation: true);

        $this->assertTrue($cell->noTruncation);
    }

    public function testChipCellNoTruncationCanBeSetToTrue(): void
    {
        $cell = CellFactory::chip('value', noTruncation: true);

        $this->assertTrue($cell->noTruncation);
    }

    public function testLinkCellNoTruncationCanBeSetToTrue(): void
    {
        $cell = CellFactory::link('value', 'https://example.com', noTruncation: true);

        $this->assertTrue($cell->noTruncation);
    }
}
