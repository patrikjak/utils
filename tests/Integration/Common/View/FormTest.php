<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Tests\Integration\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class FormTest extends TestCase
{
    public function testFormCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjutils::form action="#">
                    <x-pjutils::form.input name="name" label="Name" />
                </x-pjutils::form>
            HTML
        ));
    }

    #[DataProvider('methodDataProvider')]
    public function testFormCanBeRenderedWithMethod(string $method): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<HTML
                <x-pjutils::form method="$method" action="#">
                    <x-pjutils::form.input name="name" label="Name" />
                </x-pjutils::form>
            HTML
        ));
    }

    public function testFormCanBeRenderedWithDataAttributes(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjutils::form action="#" :data-attributes="['attr' => 'value']">
                    <x-pjutils::form.input name="name" label="Name" />
                </x-pjutils::form>
            HTML
        ));
    }

    public function testFormCanBeRenderedWithRedirect(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjutils::form action="#" redirect="http://localhost">
                    <x-pjutils::form.input name="name" label="Name" />
                </x-pjutils::form>
            HTML
        ));
    }

    /**
     * @return iterable<array{string}>
     */
    public static function methodDataProvider(): iterable
    {
        yield 'GET' => ['GET'];
        yield 'POST' => ['POST'];
        yield 'PUT' => ['PUT'];
        yield 'PATCH' => ['PATCH'];
        yield 'DELETE' => ['DELETE'];
    }
}