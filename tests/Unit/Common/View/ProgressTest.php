<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Common\View;

use Patrikjak\Utils\Common\Enums\ProgressType;
use Patrikjak\Utils\Common\View\Progress;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ProgressTest extends TestCase
{
    public function testDefaultProducesBaseClassOnly(): void
    {
        $progress = new Progress();

        $this->assertSame('pj-progress', $progress->classes);
    }

    #[DataProvider('typedProgressProvider')]
    public function testTypedProgressAddsTypeClass(ProgressType $type, string $expectedClass): void
    {
        $progress = new Progress(type: $type);

        $this->assertSame($expectedClass, $progress->classes);
    }

    public function testValueIsClamped(): void
    {
        $this->assertSame(100, (new Progress(value: 150))->clampedValue);
        $this->assertSame(0, (new Progress(value: -10))->clampedValue);
        $this->assertSame(50, (new Progress(value: 50))->clampedValue);
    }

    public function testZeroValueClampsToZero(): void
    {
        $this->assertSame(0, (new Progress(value: 0))->clampedValue);
    }

    public function testHundredValueClampsToHundred(): void
    {
        $this->assertSame(100, (new Progress(value: 100))->clampedValue);
    }

    /**
     * @return iterable<string, array{ProgressType, string}>
     */
    public static function typedProgressProvider(): iterable
    {
        yield 'success' => [ProgressType::SUCCESS, 'pj-progress success'];
        yield 'danger' => [ProgressType::DANGER, 'pj-progress danger'];
        yield 'warning' => [ProgressType::WARNING, 'pj-progress warning'];
    }
}
