<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Patrikjak\Utils\Common\Enums\WidgetSize;
use Patrikjak\Utils\Tests\Integration\TestCase;

final class WidgetTest extends TestCase
{
    public function testWidgetCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::widget>Content</x-pjutils::widget>');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testWidgetCanBeRenderedWithTitle(): void
    {
        $view = $this->blade('<x-pjutils::widget title="My Widget">Content</x-pjutils::widget>');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testWidgetCanBeRenderedWithTitleAndSubtitle(): void
    {
        $view = $this->blade(
            '<x-pjutils::widget title="Orders" subtitle="Last 7 days">Content</x-pjutils::widget>',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testWidgetCanBeRenderedWithSmSize(): void
    {
        $view = $this->blade(
            '<x-pjutils::widget :size="$size">Content</x-pjutils::widget>',
            ['size' => WidgetSize::SM],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testWidgetCanBeRenderedWithMdSize(): void
    {
        $view = $this->blade(
            '<x-pjutils::widget :size="$size">Content</x-pjutils::widget>',
            ['size' => WidgetSize::MD],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testWidgetCanBeRenderedWithFooter(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::widget title="My Widget">
                    Content
                    <x-slot:footer>Footer content</x-slot:footer>
                </x-pjutils::widget>
            HTML,
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
