<?php

namespace Patrikjak\Utils\Tests\Integration\Common\View\Form;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Tests\Integration\TestCase;

class RadioTest extends TestCase
{
    public function testRadioCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(<<<HTML
<x-pjutils::form.radio name="agreement" label="Agree" value="yes" checked id="1" />
<x-pjutils::form.radio name="agreement" label="Disagree" value="no" id="2" />
HTML));
    }

    public function testRequiredRadioCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(<<<HTML
<x-pjutils::form.radio name="agreement" label="Agree" value="yes" required id="1" />
<x-pjutils::form.radio name="agreement" label="Disagree" value="no" id="2" />
HTML));
    }

    public function testRadioCanBeRenderedWithAttributes(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(<<<HTML
<x-pjutils::form.radio name="agreement" label="Agree" class="custom class" id="custom-id" checked value="yes" />
<x-pjutils::form.radio name="agreement" label="Disagree" class="custom class" id="custom-id2" value="no" />
HTML));
    }
}