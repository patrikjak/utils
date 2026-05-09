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
use Patrikjak\Utils\Tests\Integration\Table\Services\Implementations\PaginatedTableProvider;
use Patrikjak\Utils\Tests\Integration\Table\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

final class BasePaginatedTableProviderTest extends TestCase
{
    use MatchesSnapshots;

    private PaginatedTableProvider $tableProvider;

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
                ->action('Hide', 'hide', Icon::heroicon('heroicon-o-eye-off'), href: static fn () => 'hide', type: Type::DANGER);
        });

        $this->tableMatchesSnapshot();
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableWithBulkActions(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder
                ->checkboxes()
                ->bulkAction('Export', 'https://example.com/export')
                ->bulkAction(
                    'Delete',
                    'https://example.com/delete',
                    'DELETE',
                    Icon::heroicon('heroicon-o-trash'),
                    Type::DANGER,
                );
        });

        $this->tableMatchesSnapshot();
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableWithSingleIconBulkAction(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder
                ->checkboxes()
                ->bulkAction(
                    'Verify',
                    'https://example.com/verify',
                    'POST',
                    Icon::heroicon('heroicon-o-shield-check'),
                );
        });

        $this->tableMatchesSnapshot();
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableWithCustomPaginationOptions(): void
    {
        $this->tableProvider->configure(static function (TableBuilder $builder, ?Parameters $parameters): void {
            $builder->pageSizeOptions([5 => 5, 8 => 8, 10 => 10]);
        });

        $this->tableMatchesSnapshot();
    }

    /**
     * @throws BindingResolutionException
     */
    public function testTableLoaderIsPresent(): void
    {
        $table = $this->tableProvider->getTable(new Parameters(1, 10, null, null));
        $view = Blade::renderComponent(new Table($table));

        $this->assertStringContainsString('class="table-loader hidden"', $view);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCanGetHtmlParts(): void
    {
        $htmlParts = $this->tableProvider->getHtmlParts(new Parameters(1, 10, null, null));

        $this->assertMatchesHtmlSnapshot($htmlParts['body']);
        $this->assertMatchesHtmlSnapshot($htmlParts['pagination']);
        $this->assertMatchesHtmlSnapshot($htmlParts['head']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tableProvider = new PaginatedTableProvider();
    }

    /**
     * @throws BindingResolutionException
     */
    private function tableMatchesSnapshot(): void
    {
        $table = $this->tableProvider->getTable(new Parameters(1, 10, null, null));
        $view = Blade::renderComponent(new Table($table));

        $this->assertMatchesHtmlSnapshot($view);
    }
}
