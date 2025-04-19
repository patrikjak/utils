<?php

declare(strict_types = 1);

namespace Patrikjak\Utils;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Patrikjak\Utils\Common\Console\Commands\InstallCommand;

class UtilsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerComponentNamespaces();
        $this->publishAssets();
        $this->publishViews();
        $this->publishConfig();
        $this->publishTranslations();

        $this->extendBlade();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'pjutils');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'pjutils');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadCommands();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/pjutils.php', 'pjutils');
    }

    private function registerComponentNamespaces(): void
    {
        Blade::componentNamespace('Patrikjak\\Utils\\Common\\View', 'pjutils');
        Blade::componentNamespace('Patrikjak\\Utils\\Table\\View', 'pjutils.table');
    }

    private function publishAssets(): void
    {
        $this->publishes(
            [
                __DIR__ . '/../resources/assets/css' => resource_path('css/vendor/pjutils'),
                __DIR__ . '/../resources/assets/js' => resource_path('js/vendor/pjutils'),
                __DIR__ . '/../public' => public_path('vendor/pjutils'),
            ],
            'pjutils-assets',
        );

        $this->publishes(
            [__DIR__ . '/../resources/assets/images' => public_path('vendor/pjutils/assets/images')],
            ['pjutils-assets', 'pjutils-images'],
        );
    }

    private function publishViews(): void
    {
        $this->publishes(
            [
                __DIR__ . '/../resources/views' => resource_path('views/vendor/pjutils'),
            ],
            'pjutils-views',
        );
    }

    private function publishConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../config/pjutils.php' => config_path('pjutils.php'),
        ], 'pjutils-config');
    }

    private function publishTranslations(): void
    {
        $this->publishes([
            __DIR__ . '/../lang' => lang_path('vendor/pjutils'),
        ], 'pjutils-translations');
    }

    private function extendBlade(): void
    {
        Blade::directive('icon', static function ($icon) {
            return "<?php echo \Patrikjak\Utils\Common\Enums\Icon::from($icon)->getAsHtml(); ?>";
        });

        Blade::directive('customIcon', static function ($icon) {
            return "<?php echo \Patrikjak\Utils\Common\Enums\Icon::getCustomAsHtml($icon); ?>";
        });
    }

    private function loadCommands(): void
    {
        $this->commands([
            InstallCommand::class,
        ]);
    }
}