<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Common\View\Email;

class LayoutTest extends TestCase
{
    public function testComponentCanBeRendered(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::email.layout :use-logo="false" footer-text="Team,">
                    <p>Here you will have your HTML content</p>
                </x-pjutils::email.layout>
            HTML
        );

        $this->assertMatchesHtmlSnapshot($view);
    }
}