<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Tests\Integration\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class ButtonTest extends TestCase
{
    public function testButtonCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjutils::button>Button</x-pjutils::button>
            HTML
        ));
    }

    #[DataProvider('buttonTypeProvider')]
    public function testButtonCanBeRenderedWithButtonTypes(string $type): void
    {
        $view = $this->blade(
            '<x-pjutils::button :class="$buttonType">Button</x-pjutils::button>',
            ['buttonType' => $type],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testButtonCanBeRenderedWithLink(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::button href="http://localhost">Button</x-pjutils::button>
            HTML
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testButtonCanBeRenderedWithLoader(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::button :loading="true">Button</x-pjutils::button>
            HTML
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testButtonCanBeRenderedWithAttributes(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::button class="custom-class" id="custom-id" type="submit">Button</x-pjutils::button>
            HTML
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    #[DataProvider('buttonTypeProvider')]
    public function testBorderedButtonCanBeRendered(string $type): void
    {
        $view = $this->blade(
            '<x-pjutils::button :bordered="true" :class="$buttonType">Button</x-pjutils::button>',
            ['buttonType' => $type],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    #[DataProvider('buttonTypeProvider')]
    public function testTextedButtonCanBeRendered(string $type): void
    {
        $view = $this->blade(
            '<x-pjutils::button :texted="true" :class="$buttonType">Button</x-pjutils::button>',
            ['buttonType' => $type],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function buttonTypeProvider(): iterable
    {
        yield 'neutral' => ['neutral'];
        yield 'info' => ['info'];
        yield 'success' => ['success'];
        yield 'warning' => ['warning'];
        yield 'danger' => ['danger'];
    }
}