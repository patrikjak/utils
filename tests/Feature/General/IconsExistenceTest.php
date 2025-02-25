<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Feature\General;

use PHPUnit\Framework\TestCase;

class IconsExistenceTest extends TestCase
{
    public function testSvgAndBladeIconsExist(): void
    {
        $svgIcons = scandir(__DIR__ . '/../../../resources/assets/images/icons');
        $bladeIcons = scandir(__DIR__ . '/../../../resources/views/icons');

        $svgIcons = array_map(
            static fn (string $file) => basename($file, '.svg'),
            $svgIcons,
        );

        $svgIcons = array_filter($svgIcons, static fn (string $icon) => $icon !== '.' && $icon !== '..');

        $bladeIcons = array_map(
            static fn (string $file) => basename($file, '.blade.php'),
            $bladeIcons,
        );

        $bladeIcons = array_filter($bladeIcons, static fn (string $icon) => $icon !== '.' && $icon !== '..');

        $this->assertEquals($svgIcons, $bladeIcons);
    }
}
