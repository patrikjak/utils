<?php

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Patrikjak\Utils\Tests\Integration\TestCase;

class DropdownTest extends TestCase
{
    public function testDropdownCanBeRendered(): void
    {
        $view = $this->blade(
            <<<HTML
                <x-pjutils::dropdown :items="['Item 1', 'Item 2', 'Item 3']" label="Simple dropdown" />
            HTML
        );

        $this->assertMatchesHtmlSnapshot($view);
    }

    public function testDropdownCanBeRenderedWithSelectedValue(): void
    {
        $view = $this->blade(
            <<<HTML
                <x-pjutils::dropdown 
                    :items="['item1' => 'Item 1', 'item2' => 'Item 2', 'item3' => 'Item 3']" 
                    label="Simple dropdown" 
                    selected="item3" 
                />
            HTML
        );

        $this->assertMatchesHtmlSnapshot($view);
    }
}