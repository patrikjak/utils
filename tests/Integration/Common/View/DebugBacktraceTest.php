<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Patrikjak\Utils\Tests\Integration\TestCase;

class DebugBacktraceTest extends TestCase
{
    public function testDebugBacktraceCanBeRenderedWithFrames(): void
    {
        $frames = [
            [
                'file' => 'app/Http/Controllers/UserController.php',
                'line' => 42,
                'callable' => 'App\\Http\\Controllers\\UserController->index',
            ],
            [
                'file' => 'vendor/laravel/framework/src/Illuminate/Routing/Router.php',
                'line' => 787,
                'callable' => 'Illuminate\\Routing\\Router->runRoute',
            ],
        ];

        $view = $this->blade(
            '<x-pjutils::debug-backtrace :frames="$frames" />',
            ['frames' => $frames],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testDebugBacktraceCanBeRenderedWithTitleAndMessage(): void
    {
        $frames = [
            [
                'file' => 'app/Services/PaymentService.php',
                'line' => 88,
                'callable' => 'App\\Services\\PaymentService->charge',
            ],
        ];

        $view = $this->blade(
            '<x-pjutils::debug-backtrace :frames="$frames" title="RuntimeException" message="Connection refused" />',
            ['frames' => $frames],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testDebugBacktraceCanBeRenderedWithRawTrace(): void
    {
        $trace = "#0 app/Services/PaymentService.php(88): PaymentService->charge()\n"
            . "#1 app/Http/Controllers/OrderController.php(34): OrderController->store()\n"
            . "#2 vendor/laravel/framework/src/Illuminate/Routing/Router.php(787): {closure}()";

        $view = $this->blade(
            '<x-pjutils::debug-backtrace :trace="$trace" title="Error Trace" />',
            ['trace' => $trace],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testDebugBacktraceWithNoFrames(): void
    {
        $view = $this->blade('<x-pjutils::debug-backtrace />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testVendorFrameAutoDetection(): void
    {
        $frames = [
            ['file' => 'app/Foo.php', 'line' => 1, 'callable' => 'Foo->bar'],
            ['file' => 'vendor/some/package/Baz.php', 'line' => 2, 'callable' => 'Baz->qux'],
        ];

        $rendered = (string) $this->blade(
            '<x-pjutils::debug-backtrace :frames="$frames" />',
            ['frames' => $frames],
        );

        $this->assertStringContainsString('vendor', $rendered);
    }

    public function testDebugBacktraceCanBeRenderedWithLines(): void
    {
        $lines = [
            '#0 app/Http/Controllers/UserController.php(42): UserController->index()',
            '#1 app/Http/Middleware/Auth.php(30): Auth->handle()',
            '#2 vendor/laravel/framework/src/Illuminate/Routing/Router.php(787): {closure}()',
            '#3 vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(200): Kernel->handle()',
        ];

        $view = $this->blade(
            '<x-pjutils::debug-backtrace :lines="$lines" title="Parsed Trace" />',
            ['lines' => $lines],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testLinesVendorAutoDetection(): void
    {
        $lines = [
            '#0 app/Foo.php(1): Foo->bar()',
            '#1 vendor/some/package/Baz.php(2): Baz->qux()',
        ];

        $rendered = (string) $this->blade(
            '<x-pjutils::debug-backtrace :lines="$lines" />',
            ['lines' => $lines],
        );

        $this->assertStringContainsString('vendor', $rendered);
    }
}
