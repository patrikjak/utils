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
    }

    public function register(): void
    {
    }
}