<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Common;

use Patrikjak\Utils\Common\Exceptions\IconFileNotFoundException;
use Patrikjak\Utils\Common\Icon;
use PHPUnit\Framework\TestCase;

final class IconTest extends TestCase
{
    public function testHeroiconCreatesInstance(): void
    {
        $this->assertInstanceOf(Icon::class, Icon::heroicon('heroicon-o-trash'));
    }

    public function testSvgCreatesInstance(): void
    {
        $this->assertInstanceOf(Icon::class, Icon::svg('<svg></svg>'));
    }

    public function testStorageCreatesInstance(): void
    {
        $this->assertInstanceOf(Icon::class, Icon::storage('/path/to/icon.svg'));
    }

    public function testSvgToHtmlReturnsRawString(): void
    {
        $svgString = '<svg><path d="M0 0"/></svg>';

        $this->assertSame($svgString, Icon::svg($svgString)->toHtml());
    }

    public function testStorageToHtmlThrowsWhenFileNotFound(): void
    {
        $this->expectException(IconFileNotFoundException::class);

        Icon::storage('/nonexistent/icon.svg')->toHtml();
    }
}
