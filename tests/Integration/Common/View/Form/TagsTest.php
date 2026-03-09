<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View\Form;

use Patrikjak\Utils\Tests\Integration\TestCase;

final class TagsTest extends TestCase
{
    public function testEmptyTagsInputCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::form.tags name="skills" label="Skills" />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testTagsInputWithPreFilledValuesCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::form.tags name="skills" label="Skills" :value="[\'PHP\', \'Laravel\', \'TypeScript\']" />',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testTagsInputWithPlaceholderCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::form.tags name="tags" label="Tags" placeholder="Add a tag..." />',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testTagsInputWithErrorCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::form.tags name="skills" label="Skills" error="At least one skill is required." />',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
