<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Patrikjak\Utils\Tests\Integration\TestCase;

final class ClipboardTest extends TestCase
{
    public function testClipboardWithValueCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::clipboard value="sk-abc123xyz" />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testClipboardWithLabelCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::clipboard value="sk-abc123xyz" label="API Key" />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
