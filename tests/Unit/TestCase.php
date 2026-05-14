<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Patrikjak\Utils\UtilsServiceProvider;

class TestCase extends OrchestraTestCase
{
    /**
     * @inheritDoc
     */
    protected function getPackageProviders($app): array
    {
        return [UtilsServiceProvider::class];
    }
}
