<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Common\View;

use Patrikjak\Utils\Common\View\StatCard;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class StatCardTest extends TestCase
{
    public function testNoTrendIsNeutral(): void
    {
        $card = new StatCard(label: 'Users', value: '100');

        $this->assertSame('neutral', $card->trendDirection);
    }

    #[DataProvider('trendDirectionProvider')]
    public function testTrendDirectionDetection(string $trend, string $expectedDirection): void
    {
        $card = new StatCard(label: 'Users', value: '100', trend: $trend);

        $this->assertSame($expectedDirection, $card->trendDirection);
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function trendDirectionProvider(): iterable
    {
        yield 'positive percentage' => ['+12.5%', 'up'];
        yield 'negative percentage' => ['-3.2%', 'down'];
        yield 'positive number' => ['+5', 'up'];
        yield 'negative number' => ['-1', 'down'];
        yield 'zero percent' => ['0%', 'neutral'];
        yield 'tilde prefix' => ['~5%', 'neutral'];
    }
}
