<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Patrikjak\Utils\Common\Enums\BadgeType;
use Patrikjak\Utils\Tests\Integration\TestCase;

final class BadgeTest extends TestCase
{
    public function testDefaultBadgeCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::badge>Active</x-pjutils::badge>');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testSuccessBadgeCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::badge :type="$type">Active</x-pjutils::badge>',
            ['type' => BadgeType::SUCCESS],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testDangerBadgeCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::badge :type="$type">Blocked</x-pjutils::badge>',
            ['type' => BadgeType::DANGER],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testWarningBadgeCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::badge :type="$type">Pending</x-pjutils::badge>',
            ['type' => BadgeType::WARNING],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testInfoBadgeCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::badge :type="$type">Draft</x-pjutils::badge>',
            ['type' => BadgeType::INFO],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
