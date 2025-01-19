<?php

namespace Patrikjak\Utils\Tests\Integration\Common\View\Form;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Tests\Integration\TestCase;

class SelectTest extends TestCase
{
    public function testSelectCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(<<<HTML
<x-pjutils::form.select name="name" label="Name" :options="['value1' => 'Label 1', 'value2' => 'Label 2', 'value3' => 'Label 3']" />
HTML));
    }

    public function testSelectCanBeRenderedWithSelected(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<HTML
                <x-pjutils::form.select 
                    name="name" 
                    label="Name" 
                    :options="['value1' => 'Label 1', 'value2' => 'Label 2', 'value3' => 'Label 3']" value="value2" 
                />
            HTML
        ));
    }

    public function testRequiredSelectCanBeRendered(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<HTML
                <x-pjutils::form.select 
                    name="name" 
                    label="Name" 
                    required 
                    :options="['value1' => 'Label 1', 'value2' => 'Label 2', 'value3' => 'Label 3']" 
                />
            HTML
        ));
    }

    public function testSelectCanBeRenderedWithAttributes(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<HTML
                <x-pjutils::form.select 
                    name="name" 
                    label="Name" 
                    class="custom class" 
                    id="custom-id" 
                    :options="['value1' => 'Label 1', 'value2' => 'Label 2', 'value3' => 'Label 3']" 
                />
            HTML
        ));
    }

    public function testSelectCanBeRenderedWithError(): void
    {
        $this->assertMatchesHtmlSnapshot(Blade::render(
            <<<HTML
                <x-pjutils::form.select 
                    name="name" 
                    label="Name" 
                    :options="['value1' => 'Label 1', 'value2' => 'Label 2', 'value3' => 'Label 3']" 
                    error="Error message" 
                />
            HTML
        ));
    }
}