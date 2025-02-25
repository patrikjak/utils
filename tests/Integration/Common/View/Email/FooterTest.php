<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Common\View\Email;

use Orchestra\Testbench\Attributes\DefineEnvironment;

class FooterTest extends TestCase
{
    public function testComponentCanBeRendered(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::email.footer />
            HTML
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testComponentCanBeRenderedWithCustomFooterText(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::email.footer footer-text="Sincerely," />
            HTML
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    #[DefineEnvironment('setCustomAppName')]
    public function testComponentCanBeRenderedWithCustomAppName(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::email.footer />
            HTML
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}