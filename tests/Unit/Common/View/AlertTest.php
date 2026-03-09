<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Common\View;

use Patrikjak\Utils\Common\Enums\AlertType;
use Patrikjak\Utils\Common\View\Alert;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class AlertTest extends TestCase
{
    public function testDefaultTypeIsInfoClass(): void
    {
        $alert = new Alert();

        $this->assertSame('pj-alert', $alert->classes);
    }

    #[DataProvider('typedAlertProvider')]
    public function testTypedAlertAddsTypeClass(AlertType $type, string $expectedClass): void
    {
        $alert = new Alert(type: $type);

        $this->assertSame($expectedClass, $alert->classes);
    }

    /**
     * @return iterable<string, array{AlertType, string}>
     */
    public static function typedAlertProvider(): iterable
    {
        yield 'success' => [AlertType::SUCCESS, 'pj-alert success'];
        yield 'danger' => [AlertType::DANGER, 'pj-alert danger'];
        yield 'warning' => [AlertType::WARNING, 'pj-alert warning'];
    }
}
