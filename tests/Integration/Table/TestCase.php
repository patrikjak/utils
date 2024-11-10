<?php

namespace Patrikjak\Utils\Tests\Integration\Table;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            'Patrikjak\Utils\UtilsServiceProvider',
        ];
    }
}
