<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Patrikjak\Utils\Tests\Integration\TestCase;

final class TabsTest extends TestCase
{
    public function testTabsWithActiveTabCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::tabs>
                <x-pjutils::tab label="Overview" :active="true">Overview content.</x-pjutils::tab>
                <x-pjutils::tab label="Settings">Settings content.</x-pjutils::tab>
            </x-pjutils::tabs>',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testTabsWithNoActiveTabCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::tabs>
                <x-pjutils::tab label="Tab A">Content A.</x-pjutils::tab>
                <x-pjutils::tab label="Tab B">Content B.</x-pjutils::tab>
                <x-pjutils::tab label="Tab C">Content C.</x-pjutils::tab>
            </x-pjutils::tabs>',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testSingleTabCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::tab label="Details" :active="true">Some details here.</x-pjutils::tab>',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
