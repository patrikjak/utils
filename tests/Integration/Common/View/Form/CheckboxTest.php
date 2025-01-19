<?php

namespace Patrikjak\Utils\Tests\Integration\Common\View\Form;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Tests\Integration\TestCase;

class CheckboxTest extends TestCase
{
    public function testCheckboxCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(<<<HTML
<x-pjutils::form.checkbox name="agreement" label="Agree" />
HTML));
    }

    public function testRequiredCheckboxCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(<<<HTML
<x-pjutils::form.checkbox name="agreement" label="Agree" required />
HTML));
    }

    public function testCheckboxCanBeRenderedWithAttributes(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(<<<HTML
<x-pjutils::form.checkbox name="agreement" label="Agree" class="custom class" id="custom-id" />
HTML));
    }
}