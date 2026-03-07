<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Patrikjak\Utils\Tests\Integration\TestCase;

class WidgetGridTest extends TestCase
{
    public function testWidgetGridCanBeRendered(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::widget-grid>
                    <x-pjutils::widget title="A">Content A</x-pjutils::widget>
                    <x-pjutils::widget title="B">Content B</x-pjutils::widget>
                </x-pjutils::widget-grid>
            HTML,
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testWidgetGridThreeCols(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::widget-grid :cols="3">
                    <x-pjutils::widget title="A">Content A</x-pjutils::widget>
                    <x-pjutils::widget title="B">Content B</x-pjutils::widget>
                    <x-pjutils::widget title="C">Content C</x-pjutils::widget>
                </x-pjutils::widget-grid>
            HTML,
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testWidgetGridWithColSpan(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::widget-grid :cols="3">
                    <x-pjutils::widget title="Wide" :col-span="2">Spans two columns</x-pjutils::widget>
                    <x-pjutils::widget title="Narrow">One column</x-pjutils::widget>
                </x-pjutils::widget-grid>
            HTML,
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testWidgetGridWithSmGap(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::widget-grid gap="sm">
                    <x-pjutils::widget title="A">Content A</x-pjutils::widget>
                    <x-pjutils::widget title="B">Content B</x-pjutils::widget>
                </x-pjutils::widget-grid>
            HTML,
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
