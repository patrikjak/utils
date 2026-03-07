<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Common\View;

use Patrikjak\Utils\Common\View\Alert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AlertTest extends TestCase
{
    public function testDefaultTypeIsInfoClass(): void
    {
        $alert = new Alert();

        $this->assertSame('pj-alert', $alert->classes);
    }

    #[DataProvider('typedAlertProvider')]
    public function testTypedAlertAddsTypeClass(string $type, string $expectedClass): void
    {
        $alert = new Alert(type: $type);

        $this->assertSame($expectedClass, $alert->classes);
    }

    public function testUnknownTypeDoesNotAddExtraClass(): void
    {
        $alert = new Alert(type: 'custom');

        $this->assertSame('pj-alert', $alert->classes);
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function typedAlertProvider(): iterable
    {
        yield 'success' => ['success', 'pj-alert success'];
        yield 'danger' => ['danger', 'pj-alert danger'];
        yield 'warning' => ['warning', 'pj-alert warning'];
    }
}
