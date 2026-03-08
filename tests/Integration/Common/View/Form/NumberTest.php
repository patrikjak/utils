<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View\Form;

use Patrikjak\Utils\Tests\Integration\TestCase;

final class NumberTest extends TestCase
{
    public function testDefaultNumberInputCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::form.number name="quantity" />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testNumberInputWithLabelCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::form.number name="quantity" label="Quantity" />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testNumberInputWithMinMaxCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::form.number name="quantity" label="Quantity" :min="1" :max="99" :value="5" />',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testNumberInputWithStepCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::form.number name="price" label="Price" :step="0.01" :value="9.99" />',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testNumberInputWithErrorCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::form.number name="quantity" label="Quantity" error="Value must be between 1 and 10." />',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
