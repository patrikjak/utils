<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Patrikjak\Utils\Tests\Integration\TestCase;

final class ClipboardBtnTest extends TestCase
{
    public function testClipboardBtnCanBeRendered(): void
    {
        $view = $this->blade('<x-pjutils::clipboard-btn value="sk-abc123xyz" />');

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
