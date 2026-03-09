<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Patrikjak\Utils\Common\Enums\ProgressType;
use Patrikjak\Utils\Tests\Integration\TestCase;

final class ProgressTest extends TestCase
{
    public function testDefaultProgressCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::progress :value="50" />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testProgressWithLabelCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::progress :value="65" label="Profile completion" />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testProgressWithoutLabelCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::progress :value="40" :show-label="false" />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testSuccessProgressCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::progress :value="100" :type="$type" label="Complete" />',
            ['type' => ProgressType::SUCCESS],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testDangerProgressCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::progress :value="20" :type="$type" />',
            ['type' => ProgressType::DANGER],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testWarningProgressCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::progress :value="55" :type="$type" />',
            ['type' => ProgressType::WARNING],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testValueIsClamped(): void
    {
        $view = $this->blade('<x-pjutils::progress :value="150" />');

        $this->assertStringContainsString('width: 100%', (string) $view);
    }

    public function testNegativeValueIsClamped(): void
    {
        $view = $this->blade('<x-pjutils::progress :value="-10" />');

        $this->assertStringContainsString('width: 0%', (string) $view);
    }
}
