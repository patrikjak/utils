<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Patrikjak\Utils\Tests\Integration\TestCase;

class EmptyStateTest extends TestCase
{
    public function testEmptyStateCanBeRenderedWithMessageOnly(): void
    {
        $view = $this->blade('<x-pjutils::empty-state message="No records found" />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testEmptyStateCanBeRenderedWithDescription(): void
    {
        $view = $this->blade(
            '<x-pjutils::empty-state message="No orders yet" description="Orders you create will appear here." />',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testEmptyStateCanBeRenderedWithAction(): void
    {
        $view = $this->blade(
            <<<'HTML'
                <x-pjutils::empty-state message="No users found">
                    <x-pjutils::button>Add user</x-pjutils::button>
                </x-pjutils::empty-state>
            HTML,
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
