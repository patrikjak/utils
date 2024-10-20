<?php

namespace Patrikjak\Utils;


use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class UtilsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'pjutils');

        Blade::componentNamespace('Patrikjak\\Utils\\View\\Components', 'pjutils');

        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__ . '/../resources/views' => resource_path('views/vendor/pjutils'),
                    __DIR__ . '/../resources/assets/css' => resource_path('css/vendor/pjutils'),
                    __DIR__ . '/../resources/assets/js' => resource_path('js/vendor/pjutils'),
                ],
                'assets',
            );
        }

        $this->publishes(
            [__DIR__ . '/../public' => public_path('vendor/pjutils')],
            'assets',
        );
    }

    public function register(): void
    {
    }
}