<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common;

use Patrikjak\Utils\Common\Icon;
use Patrikjak\Utils\Tests\Integration\TestCase;

final class IconTest extends TestCase
{
    public function testHeroiconToHtmlReturnsSvg(): void
    {
        $html = Icon::heroicon('heroicon-o-trash')->toHtml();

        $this->assertStringContainsString('<svg', $html);
        $this->assertStringContainsString('</svg>', $html);
    }

    public function testSvgToHtmlReturnsRawString(): void
    {
        $svgString = '<svg><path d="M0 0"/></svg>';
        $html = Icon::svg($svgString)->toHtml();

        $this->assertSame($svgString, $html);
    }
}
