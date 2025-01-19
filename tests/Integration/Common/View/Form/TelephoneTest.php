<?php

namespace Patrikjak\Utils\Tests\Integration\Common\View\Form;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Tests\Integration\TestCase;

class TelephoneTest extends TestCase
{
    public function testTelephoneInputCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<HTML
                <x-pjutils::form.telephone name="name" label="Name" />
            HTML
        ));
    }

    public function testTelephoneInputCanBeRenderedWithCustomPattern(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<HTML
                <x-pjutils::form.telephone name="name" label="Name" pattern="^\+420\d{9}$" />
            HTML
        ));
    }

    public function testRequiredTelephoneInputCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<HTML
                <x-pjutils::form.telephone name="name" label="Name" required />
            HTML
        ));
    }

    public function testTelephoneInputCanBeRenderedWithAttributes(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<HTML
                <x-pjutils::form.telephone name="name" label="Name" class="custom class" id="custom-id" />
            HTML
        ));
    }

    public function testTelephoneInputCanBeRenderedWithError(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<HTML
                <x-pjutils::form.telephone name="name" label="Name" error="Error message" />
            HTML
        ));
    }
}