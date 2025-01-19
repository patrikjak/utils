<?php

namespace Patrikjak\Utils\Tests\Integration\Common\View\Form;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Tests\Integration\TestCase;

class FileTest extends TestCase
{
    public function testFileCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<HTML
                <x-pjutils::form.file name="file" label="File" />
            HTML
        ));
    }

    public function testRequiredFileCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<HTML
                <x-pjutils::form.file name="file" label="File" required />
            HTML
        ));
    }

    public function testFileCanBeRenderedWithAttributes(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<HTML
                <x-pjutils::form.file name="file" label="File" class="custom class" id="custom-id" />
            HTML
        ));
    }
}