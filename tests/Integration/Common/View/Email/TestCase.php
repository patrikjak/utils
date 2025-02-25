<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Common\View\Email;

use Illuminate\Foundation\Application;
use Patrikjak\Utils\Tests\Integration\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function setCustomAppName(Application $app): void
    {
        $app['config']->set('pjutils.app_name', 'Custom App Name');
    }

    protected function setCustomEmailLogoPath(Application $app): void
    {
        $app['config']->set('pjutils.email_logo_path', 'custom-logo.png');
    }

    protected function setCustomAppUrl(Application $app): void
    {
        $app['config']->set('pjutils.app_url', 'http://example.com');
    }
}