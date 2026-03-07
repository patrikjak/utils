<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Common\View;

use Patrikjak\Utils\Tests\Integration\TestCase;

class StatCardTest extends TestCase
{
    public function testStatCardCanBeRendered(): void
    {
        $view = $this->blade(
            '<x-pjutils::stat-card label="Total Users" value="1,234" />',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testStatCardCanBeRenderedWithPositiveTrend(): void
    {
        $view = $this->blade(
            '<x-pjutils::stat-card label="Revenue" value="€42,000" trend="+12.5%" />',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testStatCardCanBeRenderedWithNegativeTrend(): void
    {
        $view = $this->blade(
            '<x-pjutils::stat-card label="Churn" value="3.2%" trend="-0.8%" />',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }

    public function testStatCardCanBeRenderedWithNeutralTrend(): void
    {
        $view = $this->blade(
            '<x-pjutils::stat-card label="Uptime" value="99.9%" trend="0%" />',
        );

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
