<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Table\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Common\Icon;
use Patrikjak\Utils\Table\Builder\TableBuilder;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\View\Table;
use Patrikjak\Utils\Tests\Integration\Table\Services\Implementations\TableProvider;
use Patrikjak\Utils\Tests\Integration\Table\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

final class BaseTableProviderTest extends TestCase
{
    use MatchesSnapshots;

    private TableProvider $tableProvider;

    /**
     * @throws BindingResolutionException
     */
    public function testTableCanBeRendered(): void
    {
        $this->tableMatchesSnapshot();
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableWithDifferentRowId(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder->rowId('email');
        });

        $this->tableMatchesSnapshot();
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableWithOrderDisplayedCanBeRendered(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder->order();
        });

        $this->tableMatchesSnapshot();
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableWithCheckboxesCanBeRendered(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder->checkboxes();
        });

        $this->tableMatchesSnapshot();
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableWithActions(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder
                ->action('Edit', 'edit', href: static fn (array $row) => 'edit')
                ->action('Delete', 'delete', href: static fn () => 'delete', type: Type::DANGER)
                ->action('Show', 'show', Icon::heroicon('heroicon-o-eye'), href: static fn () => 'show')
                ->action('Hide', 'hide', Icon::heroicon('heroicon-o-eye-off'), href: static fn () => 'hide', type: Type::DANGER)
                ->action(
                    'Hidden for some rows',
                    'hidden-for-some-rows',
                    href: static fn () => 'dynamic',
                    when: static fn (array $row) => $row['id'] !== '1',
                )
                ->action('Hidden for all items', 'hidden-for-all-items', href: static fn () => 'hidden', when: static fn () => false)
                ->action('Static link', 'static-link', href: static fn () => 'https://google.com')
                ->action('Dynamic link', 'dynamic-link', href: static fn (array $row) => sprintf('dynamic-link/%s', $row['id']))
                ->action('Different method', 'different-method', href: static fn () => 'https://example.com/different-method', method: 'POST');
        });

        $this->tableMatchesSnapshot();
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableWithSingleIconAction(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder->action('Verify', 'verify', Icon::heroicon('heroicon-o-shield-check'), href: static fn () => 'verify');
        });

        $this->tableMatchesSnapshot();
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableWithInlineActions(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder
                ->action('Edit', 'edit', href: static fn () => 'edit', inline: true)
                ->action('Delete', 'delete', href: static fn () => 'delete', type: Type::DANGER, inline: true);
        });

        $this->tableMatchesSnapshot();
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableWithMixedActions(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder
                ->action('Edit', 'edit', href: static fn () => 'edit', inline: true)
                ->action('Delete', 'delete', href: static fn () => 'delete', type: Type::DANGER)
                ->action('Hidden inline', 'hidden-inline', href: static fn () => 'hidden-inline', when: static fn () => false, inline: true)
                ->action(
                    'Dynamic inline',
                    'dynamic-inline',
                    href: static fn (array $row) => sprintf('edit/%s', $row['id']),
                    inline: true,
                );
        });

        $this->tableMatchesSnapshot();
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableWithDefaultMaxLengthCanBeRendered(): void
    {
        config()->set('pjutils.table.default_max_length', 10);

        $this->tableMatchesSnapshot();
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableUsesConfigDefaultMaxLength(): void
    {
        config()->set('pjutils.table.default_max_length', 8);

        $provider = new Implementations\MinimalTableProvider();
        $table = $provider->getTable();
        $view = Blade::renderComponent(new Table($table));

        $this->assertMatchesHtmlSnapshot($view);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableLoaderIsAbsentForStaticTable(): void
    {
        $table = $this->tableProvider->getTable();
        $view = Blade::renderComponent(new Table($table));

        $this->assertStringNotContainsString('table-loader', $view);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tableProvider = new TableProvider();
    }

    /**
     * @throws BindingResolutionException
     */
    private function tableMatchesSnapshot(): void
    {
        $table = $this->tableProvider->getTable();
        $view = Blade::renderComponent(new Table($table));

        $this->assertMatchesHtmlSnapshot($view);
    }
}
