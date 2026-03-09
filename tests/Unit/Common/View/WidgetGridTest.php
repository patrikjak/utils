<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Common\View;

use Patrikjak\Utils\Common\Enums\WidgetGridGap;
use Patrikjak\Utils\Common\View\WidgetGrid;
use PHPUnit\Framework\TestCase;

final class WidgetGridTest extends TestCase
{
    public function testDefaultIsTwoCols(): void
    {
        $grid = new WidgetGrid();

        $this->assertSame('pj-widget-grid cols-2', $grid->classes);
    }

    public function testOneColGrid(): void
    {
        $grid = new WidgetGrid(cols: 1);

        $this->assertSame('pj-widget-grid cols-1', $grid->classes);
    }

    public function testThreeColGrid(): void
    {
        $grid = new WidgetGrid(cols: 3);

        $this->assertSame('pj-widget-grid cols-3', $grid->classes);
    }

    public function testFourColGrid(): void
    {
        $grid = new WidgetGrid(cols: 4);

        $this->assertSame('pj-widget-grid cols-4', $grid->classes);
    }

    public function testOutOfRangeColsAddsNoColsClass(): void
    {
        $grid = new WidgetGrid(cols: 5);

        $this->assertSame('pj-widget-grid', $grid->classes);
    }

    public function testDefaultGapIsMdNoClass(): void
    {
        $grid = new WidgetGrid(gap: WidgetGridGap::MD);

        $this->assertSame('pj-widget-grid cols-2', $grid->classes);
    }

    public function testSmGapAddsClass(): void
    {
        $grid = new WidgetGrid(gap: WidgetGridGap::SM);

        $this->assertSame('pj-widget-grid cols-2 gap-sm', $grid->classes);
    }

    public function testLgGapAddsClass(): void
    {
        $grid = new WidgetGrid(gap: WidgetGridGap::LG);

        $this->assertSame('pj-widget-grid cols-2 gap-lg', $grid->classes);
    }
}
