<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Patrikjak\Utils\Tests\Integration\TestCase;

final class DetailsTest extends TestCase
{
    public function testDetailsCanBeRenderedWithRowsArray(): void
    {
        $view = $this->blade(
            '<x-pjutils::details :rows="$rows" />',
            ['rows' => ['Name' => 'John Doe', 'Email' => 'john@example.com', 'Role' => 'Admin']],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testDetailsCanBeRenderedWithEmptyRows(): void
    {
        $view = $this->blade('<x-pjutils::details />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testDetailsCanBeRenderedWithSlottedRows(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::details>
                    <x-pjutils::details.row label="Status">Active</x-pjutils::details.row>
                    <x-pjutils::details.row label="Since">January 2024</x-pjutils::details.row>
                </x-pjutils::details>
            HTML,
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testDetailsRowCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::details.row label="Email">test@example.com</x-pjutils::details.row>',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
