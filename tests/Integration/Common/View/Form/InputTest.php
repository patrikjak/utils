<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Common\View\Form;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Tests\Integration\TestCase;

class InputTest extends TestCase
{
    public function testInputCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjutils::form.input name="name" label="Name" />
            HTML
        ));
    }

    public function testRequiredInputCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjutils::form.input name="name" label="Name" required />
            HTML
        ));
    }

    public function testInputCanBeRenderedWithAttributes(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjutils::form.input name="name" label="Name" class="custom class" id="custom-id" />
            HTML
        ));
    }

    public function testInputCanBeRenderedWithError(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjutils::form.input name="name" label="Name" error="Error message" />
            HTML
        ));
    }
}