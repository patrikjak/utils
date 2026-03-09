<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View\Form;

use Patrikjak\Utils\Tests\Integration\TestCase;

final class RepeaterTest extends TestCase
{
    public function testRepeaterWithSingleRowCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::form.repeater add-label="Add address">
                <x-pjutils::form.input name="addresses[{index}][street]" label="Street" />
            </x-pjutils::form.repeater>',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testRepeaterWithCustomLabelsCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::form.repeater add-label="Add item" remove-label="Delete">
                <x-pjutils::form.input name="items[{index}][name]" label="Name" />
            </x-pjutils::form.repeater>',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testRepeaterWithMaxLimitCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::form.repeater add-label="Add phone" :min="1" :max="3">
                <x-pjutils::form.input name="phones[{index}]" label="Phone number" />
            </x-pjutils::form.repeater>',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
