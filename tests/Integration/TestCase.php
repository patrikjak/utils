<?php

namespace Patrikjak\Utils\Tests\Integration;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class TestCase extends OrchestraTestCase
{
    use MatchesSnapshots;

    protected function getPackageProviders($app): array
    {
        return [
            'Patrikjak\Utils\UtilsServiceProvider',
        ];
    }
}