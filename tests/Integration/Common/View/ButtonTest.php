<?php

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
            <<<HTML
                <x-pjutils::button>Button</x-pjutils::button>
            HTML
        ));
    }

    #[DataProvider('buttonTypeProvider')]
    public function testButtonCanBeRenderedWithButtonTypes(Type $type): void
    {
        $view = $this->blade(
            '<x-pjutils::button :button-type="$buttonType">Button</x-pjutils::button>',
            ['buttonType' => $type],
        );

        $this->assertMatchesHtmlSnapshot($view);
    }

    public function testButtonCanBeRenderedWithLink(): void
    {
        $view = $this->blade(
            <<<HTML
                <x-pjutils::button href="http://localhost">Button</x-pjutils::button>
            HTML
        );

        $this->assertMatchesHtmlSnapshot($view);
    }

    public function testButtonCanBeRenderedWithLoader(): void
    {
        $view = $this->blade(
            <<<HTML
                <x-pjutils::button :loading="true">Button</x-pjutils::button>
            HTML
        );

        $this->assertMatchesHtmlSnapshot($view);
    }

    public function testButtonCanBeRenderedWithAttributes(): void
    {
        $view = $this->blade(
            <<<HTML
                <x-pjutils::button class="custom-class" id="custom-id" type="submit">Button</x-pjutils::button>
            HTML
        );

        $this->assertMatchesHtmlSnapshot($view);
    }

    #[DataProvider('buttonTypeProvider')]
    public function testBorderedButtonCanBeRendered(Type $type): void
    {
        $view = $this->blade(
            '<x-pjutils::button :bordered="true" :button-type="$buttonType">Button</x-pjutils::button>',
            ['buttonType' => $type],
        );

        $this->assertMatchesHtmlSnapshot($view);
    }

    #[DataProvider('buttonTypeProvider')]
    public function testTextedButtonCanBeRendered(Type $type): void
    {
        $view = $this->blade(
            '<x-pjutils::button :texted="true" :button-type="$buttonType">Button</x-pjutils::button>',
            ['buttonType' => $type],
        );

        $this->assertMatchesHtmlSnapshot($view);
    }

    public static function buttonTypeProvider(): iterable
    {
        yield 'neutral' => [Type::NEUTRAL];
        yield 'info' => [Type::INFO];
        yield 'success' => [Type::SUCCESS];
        yield 'warning' => [Type::WARNING];
        yield 'danger' => [Type::DANGER];
    }
}