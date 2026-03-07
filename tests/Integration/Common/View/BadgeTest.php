<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Patrikjak\Utils\Tests\Integration\TestCase;

class BadgeTest extends TestCase
{
    public function testDefaultBadgeCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::badge>Active</x-pjutils::badge>');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testSuccessBadgeCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::badge type="success">Active</x-pjutils::badge>');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testDangerBadgeCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::badge type="danger">Blocked</x-pjutils::badge>');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testWarningBadgeCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::badge type="warning">Pending</x-pjutils::badge>');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testInfoBadgeCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::badge type="info">Draft</x-pjutils::badge>');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
