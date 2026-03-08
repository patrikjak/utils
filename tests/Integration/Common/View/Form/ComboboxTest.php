<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View\Form;

use Patrikjak\Utils\Tests\Integration\TestCase;

final class ComboboxTest extends TestCase
{
    public function testComboboxWithOptionsCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::form.combobox name="country" label="Country" :options="$options" />',
            ['options' => ['sk' => 'Slovakia', 'cz' => 'Czech Republic', 'de' => 'Germany']],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testComboboxWithPreSelectedValueCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::form.combobox name="country" label="Country" :options="$options" value="sk" />',
            ['options' => ['sk' => 'Slovakia', 'cz' => 'Czech Republic']],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testComboboxWithPlaceholderCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::form.combobox name="user_id" label="Assign user"'
                . ' :options="$options" placeholder="Search users..." />',
            ['options' => [1 => 'Alice', 2 => 'Bob']],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testComboboxWithErrorCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::form.combobox name="country" label="Country" :options="$options"'
                . ' error="Please select a country." />',
            ['options' => ['sk' => 'Slovakia']],
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testComboboxWithEmptyOptionsCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::form.combobox name="category" label="Category" />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
