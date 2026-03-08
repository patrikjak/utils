<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View\Form;

use Patrikjak\Utils\Tests\Integration\TestCase;

final class ToggleTest extends TestCase
{
    public function testDefaultToggleCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::form.toggle name="is_active" />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testToggleWithLabelCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::form.toggle name="notifications" label="Email notifications" />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testCheckedToggleCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::form.toggle name="is_active" label="Active" :checked="true" />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testDisabledToggleCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::form.toggle name="is_active" label="Active" :disabled="true" />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testDisabledCheckedToggleCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::form.toggle name="is_active" label="Active" :checked="true" :disabled="true" />',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
