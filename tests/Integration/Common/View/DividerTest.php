<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Patrikjak\Utils\Tests\Integration\TestCase;

class DividerTest extends TestCase
{
    public function testDividerCanBeRenderedWithoutLabel(): void
    {
        $view = $this->blade('<x-pjutils::divider />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testDividerCanBeRenderedWithLabel(): void
    {
        $view = $this->blade('<x-pjutils::divider label="or" />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
