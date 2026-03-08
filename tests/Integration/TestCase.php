<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Patrikjak\Utils\UtilsServiceProvider;
use Spatie\Snapshots\MatchesSnapshots;

class TestCase extends OrchestraTestCase
{
    use MatchesSnapshots;
    use InteractsWithViews;

    /**
     * @inheritDoc
     */
    protected function getPackageProviders($app): array
    {
        return [
            BladeIconsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            UtilsServiceProvider::class,
        ];
    }
}
