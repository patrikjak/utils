<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Table\Services;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;
use Patrikjak\Utils\Table\Services\TableProviderInterface;
use Patrikjak\Utils\Table\View\Table;
use Patrikjak\Utils\Tests\Integration\Table\Services\Implementations\TableProvider;
use Patrikjak\Utils\Tests\Integration\Table\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class BaseTableProviderTest extends TestCase
{
    use MatchesSnapshots;

    private TableProviderInterface $tableProvider;

    private Table $table;

    public function testTableCanBeRendered(): void
    {
        $this->tableMatchesSnapshot();
    }

    public function testTableWithCustomTableIdCanBeRendered(): void
    {
        $this->tableProvider->setTableId('customTableId');

        $this->tableMatchesSnapshot();
    }

    public function testTableWithDifferentRowId(): void
    {
        $this->tableProvider->setRowId('email');

        $this->tableMatchesSnapshot();
    }

    public function testTableWithOrderDisplayedCanBeRendered(): void
    {
        $this->tableProvider->setShowOrder(true);

        $this->tableMatchesSnapshot();
    }

    public function testTableWithCheckboxesCanBeRendered(): void
    {
        $this->tableProvider->setShowCheckboxes(true);

        $this->tableMatchesSnapshot();
    }

    public function testTableWithDifferentColumnsDisplayed(): void
    {
        $this->tableProvider->setColumns(['id', 'name', 'email']);

        $this->tableMatchesSnapshot();
    }

    public function testTableWithActions(): void
    {
        $this->tableProvider->setActions([
            new Item('Edit', 'edit'),
            new Item('Delete', 'delete', type: Type::DANGER),
            new Item('Show', 'show', Icon::EYE),
            new Item('Hide', 'hide', Icon::EYE_SLASH, Type::DANGER),
        ]);

        $this->tableMatchesSnapshot();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tableProvider = new TableProvider();
    }

    private function tableMatchesSnapshot(): void
    {
        $table = $this->tableProvider->getTable();
        $view = Blade::renderComponent(new Table($table));

        $this->assertMatchesHtmlSnapshot((string) $view);
    }
}
