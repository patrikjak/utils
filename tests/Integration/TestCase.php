<?php

namespace Patrikjak\Utils\Tests\Integration;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Spatie\Snapshots\MatchesSnapshots;

class TestCase extends OrchestraTestCase
{
    use MatchesSnapshots;
    use InteractsWithViews;

    protected function getPackageProviders($app): array
    {
        return [
            'Patrikjak\Utils\UtilsServiceProvider',
        ];
    }
}