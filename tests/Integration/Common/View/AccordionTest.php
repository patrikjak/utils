<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Patrikjak\Utils\Tests\Integration\TestCase;

final class AccordionTest extends TestCase
{
    public function testClosedAccordionCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::accordion title="What is this?">Content goes here.</x-pjutils::accordion>',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testOpenAccordionCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::accordion title="What is this?" :open="true">Content goes here.</x-pjutils::accordion>',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testAccordionGroupCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::accordion-group>
                <x-pjutils::accordion title="Section 1">First section content.</x-pjutils::accordion>
                <x-pjutils::accordion title="Section 2" :open="true">Second section content.</x-pjutils::accordion>
                <x-pjutils::accordion title="Section 3">Third section content.</x-pjutils::accordion>
            </x-pjutils::accordion-group>',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
