<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Common\View;

use Patrikjak\Utils\Common\Enums\WidgetHeight;
use Patrikjak\Utils\Common\Enums\WidgetSize;
use Patrikjak\Utils\Common\View\Widget;
use PHPUnit\Framework\TestCase;

final class WidgetTest extends TestCase
{
    public function testDefaultSizeIsFullClass(): void
    {
        $widget = new Widget();

        $this->assertSame('pj-widget full', $widget->classes);
    }

    public function testXsSizeAddsXsClass(): void
    {
        $widget = new Widget(size: WidgetSize::XS);

        $this->assertSame('pj-widget xs', $widget->classes);
    }

    public function testSmSizeAddsSmClass(): void
    {
        $widget = new Widget(size: WidgetSize::SM);

        $this->assertSame('pj-widget sm', $widget->classes);
    }

    public function testMdSizeAddsMdClass(): void
    {
        $widget = new Widget(size: WidgetSize::MD);

        $this->assertSame('pj-widget md', $widget->classes);
    }

    public function testHeightSmAddsHSmClass(): void
    {
        $widget = new Widget(height: WidgetHeight::SM);

        $this->assertSame('pj-widget full h-sm', $widget->classes);
    }

    public function testHeightMdAddsHMdClass(): void
    {
        $widget = new Widget(height: WidgetHeight::MD);

        $this->assertSame('pj-widget full h-md', $widget->classes);
    }

    public function testHeightLgAddsHLgClass(): void
    {
        $widget = new Widget(height: WidgetHeight::LG);

        $this->assertSame('pj-widget full h-lg', $widget->classes);
    }

    public function testHeightFullAddsHFullClass(): void
    {
        $widget = new Widget(height: WidgetHeight::FULL);

        $this->assertSame('pj-widget full h-full', $widget->classes);
    }

    public function testColSpanOneDoesNotAddClass(): void
    {
        $widget = new Widget(colSpan: 1);

        $this->assertSame('pj-widget full', $widget->classes);
    }

    public function testColSpanTwoAddsClass(): void
    {
        $widget = new Widget(colSpan: 2);

        $this->assertSame('pj-widget full col-span-2', $widget->classes);
    }

    public function testColSpanThreeAddsClass(): void
    {
        $widget = new Widget(colSpan: 3);

        $this->assertSame('pj-widget full col-span-3', $widget->classes);
    }

    public function testCombinedSizeHeightColSpan(): void
    {
        $widget = new Widget(size: WidgetSize::SM, height: WidgetHeight::MD, colSpan: 2);

        $this->assertSame('pj-widget sm h-md col-span-2', $widget->classes);
    }
}
