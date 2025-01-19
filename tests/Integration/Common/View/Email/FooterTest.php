<?php

namespace Patrikjak\Utils\Tests\Integration\Common\View\Email;

use Orchestra\Testbench\Attributes\DefineEnvironment;

class FooterTest extends TestCase
{
    public function testComponentCanBeRendered(): void
    {
        $view = $this->blade(
            <<<HTML
                <x-pjutils::email.footer />
            HTML
        );

        $this->assertMatchesHtmlSnapshot($view);
    }

    public function testComponentCanBeRenderedWithCustomFooterText(): void
    {
        $view = $this->blade(
            <<<HTML
                <x-pjutils::email.footer footer-text="Sincerely," />
            HTML
        );

        $this->assertMatchesHtmlSnapshot($view);
    }

    #[DefineEnvironment('setCustomAppName')]
    public function testComponentCanBeRenderedWithCustomAppName(): void
    {
        $view = $this->blade(
            <<<HTML
                <x-pjutils::email.footer />
            HTML
        );

        $this->assertMatchesHtmlSnapshot($view);
    }
}