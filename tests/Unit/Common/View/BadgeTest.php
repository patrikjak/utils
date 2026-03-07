<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Unit\Common\View;

use Patrikjak\Utils\Common\View\Badge;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class BadgeTest extends TestCase
{
    public function testDefaultTypeProducesBaseClassOnly(): void
    {
        $badge = new Badge();

        $this->assertSame('pj-badge', $badge->classes);
    }

    #[DataProvider('typedBadgeProvider')]
    public function testTypedBadgeAddsTypeClass(string $type, string $expectedClass): void
    {
        $badge = new Badge(type: $type);

        $this->assertSame($expectedClass, $badge->classes);
    }

    public function testUnknownTypeDoesNotAddExtraClass(): void
    {
        $badge = new Badge(type: 'custom');

        $this->assertSame('pj-badge', $badge->classes);
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function typedBadgeProvider(): iterable
    {
        yield 'success' => ['success', 'pj-badge success'];
        yield 'danger' => ['danger', 'pj-badge danger'];
        yield 'warning' => ['warning', 'pj-badge warning'];
        yield 'info' => ['info', 'pj-badge info'];
    }
}
