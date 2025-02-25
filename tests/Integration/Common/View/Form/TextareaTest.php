<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Common\View\Form;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Tests\Integration\TestCase;

class TextareaTest extends TestCase
{
    public function testTextareaCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjutils::form.textarea name="name" label="Name" />
            HTML
        ));
    }

    public function testTextareaCanBeRenderedWithValue(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjutils::form.textarea name="name" label="Name" value="Value" />
            HTML
        ));
    }

    public function testRequiredTextareaCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjutils::form.textarea name="name" label="Name" required />
            HTML
        ));
    }

    public function testTextareaCanBeRenderedWithAttributes(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<'HTML'
                <x-pjutils::form.textarea name="name" label="Name" class="custom class" id="custom-id" />
            HTML
        ));
    }
}