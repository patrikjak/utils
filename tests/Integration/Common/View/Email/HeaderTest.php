<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Common\View\Email;

use Orchestra\Testbench\Attributes\DefineEnvironment;

class HeaderTest extends TestCase
{
    public function testComponentCanBeRendered(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::email.header />
            HTML
        );

        $this->assertMatchesHtmlSnapshot($view);
    }

    public function testComponentCanBeRenderedWithoutLogo(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::email.header :use-logo="false" />
            HTML
        );

        $this->assertMatchesHtmlSnapshot($view);
    }

    #[DefineEnvironment('setCustomAppName')]
    public function testComponentCanBeRenderedWithCustomAppName(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::email.header />
            HTML
        );

        $this->assertMatchesHtmlSnapshot($view);
    }

    #[DefineEnvironment('setCustomEmailLogoPath')]
    public function testComponentCanBeRenderedWithCustomEmailLogoPath(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::email.header />
            HTML
        );

        $this->assertMatchesHtmlSnapshot($view);
    }

    #[DefineEnvironment('setCustomAppUrl')]
    public function testComponentCanBeRenderedWithCustomAppUrl(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::email.header />
            HTML
        );

        $this->assertMatchesHtmlSnapshot($view);
    }
}