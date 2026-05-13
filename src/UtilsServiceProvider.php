<?php

declare(strict_types=1);

namespace Patrikjak\Utils;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Patrikjak\Utils\Common\Console\Commands\InstallCommand;
use Patrikjak\Utils\Common\Services\QueryBuilder\Filters\JsonFilter;
use Patrikjak\Utils\Common\Services\QueryBuilder\Filters\RangeFilter;
use Patrikjak\Utils\Common\Services\QueryBuilder\Filters\SelectFilter;
use Patrikjak\Utils\Common\Services\QueryBuilder\Filters\TextFilter;
use Patrikjak\Utils\Table\Enums\Filter\FilterType;
use Patrikjak\Utils\Table\Factories\Filter\Criteria\DateFilterCriteriaFactory;
use Patrikjak\Utils\Table\Factories\Filter\Criteria\JsonFilterCriteriaFactory;
use Patrikjak\Utils\Table\Factories\Filter\Criteria\NumberFilterCriteriaFactory;
use Patrikjak\Utils\Table\Factories\Filter\Criteria\SelectFilterCriteriaFactory;
use Patrikjak\Utils\Table\Factories\Filter\Criteria\TextFilterCriteriaFactory;
use Patrikjak\Utils\Table\Registry\CellRegistry;
use Patrikjak\Utils\Table\Registry\FilterStrategyRegistry;

class UtilsServiceProvider extends ServiceProvider
{
    /**
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->registerCellRegistry();
        $this->registerFilterStrategyRegistry();
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
        $this->app->singleton(CellRegistry::class);
        $this->app->singleton(FilterStrategyRegistry::class);
    }

    private function registerCellRegistry(): void
    {
        $registry = $this->app->make(CellRegistry::class);
        $registry->register('simple', 'pjutils.table::cells.simple');
        $registry->register('two-line', 'pjutils.table::cells.two-line');
        $registry->register('chip', 'pjutils.table::cells.chip');
        $registry->register('link', 'pjutils.table::cells.link');
    }

    private function registerFilterStrategyRegistry(): void
    {
        $registry = $this->app->make(FilterStrategyRegistry::class);
        $rangeFilter = new RangeFilter();

        $registry->register(
            FilterType::Text->value,
            new TextFilter(),
            new TextFilterCriteriaFactory(),
            'pjutils::table.filter.filter-forms.text',
            'pjutils::table.filter.chips.text',
        );
        $registry->register(
            FilterType::Select->value,
            new SelectFilter(),
            new SelectFilterCriteriaFactory(),
            'pjutils::table.filter.filter-forms.select',
            'pjutils::table.filter.chips.select',
        );
        $registry->register(
            FilterType::Date->value,
            $rangeFilter,
            new DateFilterCriteriaFactory(),
            'pjutils::table.filter.filter-forms.date',
            'pjutils::table.filter.chips.date',
        );
        $registry->register(
            FilterType::Number->value,
            $rangeFilter,
            new NumberFilterCriteriaFactory(),
            'pjutils::table.filter.filter-forms.number',
            'pjutils::table.filter.chips.number',
        );
        $registry->register(
            FilterType::Json->value,
            new JsonFilter(),
            new JsonFilterCriteriaFactory(),
            'pjutils::table.filter.filter-forms.json',
            'pjutils::table.filter.chips.json',
        );
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
                __DIR__ . '/../public' => public_path('vendor/pjutils'),
            ],
            'pjutils-assets',
        );

        $this->publishes(
            [
                __DIR__ . '/../resources/assets/css' => resource_path('css/vendor/pjutils'),
                __DIR__ . '/../resources/assets/js' => resource_path('js/vendor/pjutils'),
            ],
            'pjutils-sources',
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
        Blade::directive('icon', static function (string $icon): string {
            return "<?php echo \Patrikjak\Utils\Common\Icon::heroicon($icon)->toHtml(); ?>";
        });

        Blade::directive('customIcon', static function (string $icon): string {
            return "<?php echo \Illuminate\Support\Facades\Blade::render("
                . "file_get_contents(resource_path(sprintf('views/icons/%s.blade.php', $icon))) ?: ''"
                . "); ?>";
        });
    }

    private function loadCommands(): void
    {
        $this->commands([
            InstallCommand::class,
        ]);
    }
}
