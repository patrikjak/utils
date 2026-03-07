<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Patrikjak\Utils\Tests\Integration\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class AlertTest extends TestCase
{
    public function testDefaultAlertCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::alert>Something happened.</x-pjutils::alert>');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    #[DataProvider('alertTypeProvider')]
    public function testTypedAlertCanBeRendered(string $type): void
    {
        $view = $this->blade(
            '<x-pjutils::alert :type="$type">Alert message.</x-pjutils::alert>',
            ['type' => $type],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testAlertCanBeRenderedWithTitle(): void
    {
        $view = $this->blade(
            '<x-pjutils::alert type="success" title="Saved">Changes saved successfully.</x-pjutils::alert>',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testNonDismissibleAlertHasNoDismissButton(): void
    {
        $view = $this->blade(
            '<x-pjutils::alert :dismissible="false">Read only alert.</x-pjutils::alert>',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function alertTypeProvider(): iterable
    {
        yield 'success' => ['success'];
        yield 'danger' => ['danger'];
        yield 'warning' => ['warning'];
        yield 'info' => ['info'];
    }
}
