<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Common\View;

use Patrikjak\Utils\Common\Enums\BadgeType;
use Patrikjak\Utils\Common\View\Badge;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class BadgeTest extends TestCase
{
    public function testDefaultTypeProducesBaseClassOnly(): void
    {
        $badge = new Badge();

        $this->assertSame('pj-badge', $badge->classes);
    }

    #[DataProvider('typedBadgeProvider')]
    public function testTypedBadgeAddsTypeClass(BadgeType $type, string $expectedClass): void
    {
        $badge = new Badge(type: $type);

        $this->assertSame($expectedClass, $badge->classes);
    }

    /**
     * @return iterable<string, array{BadgeType, string}>
     */
    public static function typedBadgeProvider(): iterable
    {
        yield 'success' => [BadgeType::SUCCESS, 'pj-badge success'];
        yield 'danger' => [BadgeType::DANGER, 'pj-badge danger'];
        yield 'warning' => [BadgeType::WARNING, 'pj-badge warning'];
        yield 'info' => [BadgeType::INFO, 'pj-badge info'];
    }
}
