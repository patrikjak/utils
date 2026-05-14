<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Unit\Table\Builder;

use Illuminate\Contracts\Container\BindingResolutionException;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Common\Icon;
use Patrikjak\Utils\Table\Builder\Cell;
use Patrikjak\Utils\Table\Builder\Filter;
use Patrikjak\Utils\Table\Builder\TableBuilder;
use Patrikjak\Utils\Table\Dto\Filter\Settings as FilterSettings;
use Patrikjak\Utils\Table\Dto\Search\Settings as SearchSettings;
use Patrikjak\Utils\Table\Dto\Sort\Settings as SortSettings;
use Patrikjak\Utils\Table\Exceptions\InvalidTableBuilderException;
use Patrikjak\Utils\Table\ValueObjects\Cells\Actions\Item as ActionItem;
use Patrikjak\Utils\Table\ValueObjects\ColumnVisibility;
use Patrikjak\Utils\Table\ValueObjects\EmptyState;
use Patrikjak\Utils\Tests\Integration\TestCase;

final class TableBuilderTest extends TestCase
{
    /**
     * @var array<array<string, string>>
     */
    private array $rows = [
        ['id' => '1', 'name' => 'Alice', 'email' => 'alice@example.com'],
        ['id' => '2', 'name' => 'Bob', 'email' => 'bob@example.com'],
    ];

    /**
     * @throws BindingResolutionException
     */
    public function testAssembleTableProducesTableDto(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->assembleTable(null);

        $this->assertSame('users', $table->tableId);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testHeaderDerivedFromColumnLabels(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->column('email', 'Email', fn (array $r) => Cell::simple($r['email']))
            ->assembleTable(null);

        $this->assertSame(['name' => 'Name', 'email' => 'Email'], $table->header->all());
    }

    /**
     * @throws BindingResolutionException
     */
    public function testDataRenderedThroughColumnClosures(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->assembleTable(null);

        $this->assertCount(2, $table->data);
        $this->assertSame('Alice', (string) $table->data[0]['name']);
        $this->assertSame('Bob', (string) $table->data[1]['name']);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testRowIdExtractedFromEachRow(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->assembleTable(null);

        $this->assertSame('1', $table->data[0]['id']);
        $this->assertSame('2', $table->data[1]['id']);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCustomRowId(): void
    {
        $rows = [['uuid' => 'abc-123', 'name' => 'Alice']];

        $table = TableBuilder::for('users', $rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->rowId('uuid')
            ->assembleTable(null);

        $this->assertSame('abc-123', $table->data[0]['uuid']);
        $this->assertSame('uuid', $table->rowId);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testHiddenColumnExcludedFromHeaderAndData(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->column('notes', 'Notes', fn (array $r) => Cell::simple('note'), hidden: true)
            ->assembleTable(null);

        $this->assertTrue($table->header->has('name'));
        $this->assertFalse($table->header->has('notes'));
        $this->assertArrayHasKey('name', $table->data[0]);
        $this->assertArrayNotHasKey('notes', $table->data[0]);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testHiddenColumnPopulatesColumnVisibility(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->column('notes', 'Notes', fn (array $r) => Cell::simple('note'), hidden: true)
            ->assembleTable(null);

        $this->assertInstanceOf(ColumnVisibility::class, $table->columnVisibility);
        $this->assertContains('notes', $table->columnVisibility->defaultHidden);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testNoHiddenColumnsProducesNullColumnVisibility(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->assembleTable(null);

        $this->assertNull($table->columnVisibility);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSortSettingsDerivedFromSortKeys(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->column('email', 'Email', fn (array $r) => Cell::simple($r['email']))
            ->sort('name', 'email')
            ->assembleTable(null);

        $this->assertInstanceOf(SortSettings::class, $table->sortSettings);
        $this->assertCount(2, $table->sortSettings->sortableColumns);
        $this->assertSame('name', $table->sortSettings->sortableColumns[0]->column);
        $this->assertSame('email', $table->sortSettings->sortableColumns[1]->column);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSortUsesDbAliasWhenSet(): void
    {
        $table = TableBuilder::for('users', $this->rows, ['name' => 'u.name'])
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->sort('name')
            ->assembleTable(null);

        $this->assertSame('u.name', $table->sortSettings->sortableColumns[0]->column);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testNoSortKeysProducesNullSortSettings(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->assembleTable(null);

        $this->assertNull($table->sortSettings);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSortKeyDeduplication(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->sort('name')
            ->sort('name')
            ->assembleTable(null);

        $this->assertCount(1, $table->sortSettings->sortableColumns);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testFilterSettingsDerivedFromFilterKeys(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->filter('name', Filter::text())
            ->assembleTable(null);

        $this->assertInstanceOf(FilterSettings::class, $table->filterSettings);
        $this->assertCount(1, $table->filterSettings->filterableColumns);
        $this->assertSame('name', $table->filterSettings->filterableColumns[0]->column);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testFilterDefaultsToText(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->filter('name')
            ->assembleTable(null);

        $this->assertInstanceOf(FilterSettings::class, $table->filterSettings);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testNoFilterKeysProducesNullFilterSettings(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->assembleTable(null);

        $this->assertNull($table->filterSettings);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSearchSettingsDerivedFromSearchKeys(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->column('email', 'Email', fn (array $r) => Cell::simple($r['email']))
            ->search('name', 'email')
            ->assembleTable(null);

        $this->assertInstanceOf(SearchSettings::class, $table->searchSettings);
        $this->assertCount(2, $table->searchSettings->searchableColumns);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSearchUsesDbAliasWhenSet(): void
    {
        $table = TableBuilder::for('users', $this->rows, ['name' => 'u.name'])
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->search('name')
            ->assembleTable(null);

        $this->assertSame('u.name', $table->searchSettings->searchableColumns[0]);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testSearchKeyDeduplication(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->search('name')
            ->search('name')
            ->assembleTable(null);

        $this->assertCount(1, $table->searchSettings->searchableColumns);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testNoSearchKeysProducesNullSearchSettings(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->assembleTable(null);

        $this->assertNull($table->searchSettings);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testActionsBuiltCorrectly(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->action('Edit', 'edit', href: fn (array $r) => '/edit/' . $r['id'])
            ->action(
                'Delete',
                'delete',
                type: Type::DANGER,
                method: 'DELETE',
                href: fn (array $r) => '/delete/' . $r['id']
            )
            ->assembleTable(null);

        $this->assertCount(2, $table->actions);
        $this->assertInstanceOf(ActionItem::class, $table->actions[0]);
        $this->assertSame('Edit', $table->actions[0]->label);
        $this->assertSame('edit', $table->actions[0]->classId);
        $this->assertNull($table->actions[0]->method);
        $this->assertSame('DELETE', $table->actions[1]->method);
        $this->assertSame(Type::DANGER, $table->actions[1]->type);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testActionWhenClosureAssignedToVisible(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->action('Edit', 'edit', when: fn (array $r) => $r['id'] === '1')
            ->assembleTable(null);

        $visible = $table->actions[0]->visible;
        $this->assertIsCallable($visible);
        $this->assertTrue($visible(['id' => '1', 'name' => 'Alice']));
        $this->assertFalse($visible(['id' => '2', 'name' => 'Bob']));
    }

    /**
     * @throws BindingResolutionException
     */
    public function testActionWhenNotClosureNegated(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->action('Delete', 'delete', whenNot: fn (array $r) => $r['id'] === '1')
            ->assembleTable(null);

        $visible = $table->actions[0]->visible;
        $this->assertIsCallable($visible);
        $this->assertFalse($visible(['id' => '1', 'name' => 'Alice']));
        $this->assertTrue($visible(['id' => '2', 'name' => 'Bob']));
    }

    /**
     * @throws BindingResolutionException
     */
    public function testActionInlineFlagPassedThrough(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->action('Edit', 'edit', inline: true)
            ->assembleTable(null);

        $this->assertTrue($table->actions[0]->inline);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCheckboxes(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->checkboxes()
            ->assembleTable(null);

        $this->assertTrue($table->showCheckboxes);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testOrder(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->order()
            ->assembleTable(null);

        $this->assertTrue($table->showOrder);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testNoPaginationForPlainArray(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->assembleTable(null);

        $this->assertNull($table->paginationSettings);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCollectionDataProducesNoPagination(): void
    {
        $table = TableBuilder::for('users', collect($this->rows))
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->assembleTable(null);

        $this->assertNull($table->paginationSettings);
        $this->assertCount(2, $table->data);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testValidationThrowsForUnknownSortKey(): void
    {
        $this->expectException(InvalidTableBuilderException::class);
        $this->expectExceptionMessage('sort key "missing" does not match any column');

        TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->sort('missing')
            ->assembleTable(null);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testValidationThrowsForUnknownFilterKey(): void
    {
        $this->expectException(InvalidTableBuilderException::class);
        $this->expectExceptionMessage('filter key "missing" does not match any column');

        TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->filter('missing')
            ->assembleTable(null);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testValidationThrowsForUnknownSearchKey(): void
    {
        $this->expectException(InvalidTableBuilderException::class);
        $this->expectExceptionMessage('search key "missing" does not match any column');

        TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->search('missing')
            ->assembleTable(null);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testValidationThrowsForBulkActionWithoutCheckboxes(): void
    {
        $this->expectException(InvalidTableBuilderException::class);
        $this->expectExceptionMessage('bulkAction() requires checkboxes() to be called');

        TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->bulkAction('Delete', '/delete', 'DELETE')
            ->assembleTable(null);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testValidationThrowsForZeroColumns(): void
    {
        $this->expectException(InvalidTableBuilderException::class);
        $this->expectExceptionMessage('at least one column() must be defined');

        TableBuilder::for('users', $this->rows)
            ->assembleTable(null);
    }

    public function testValidationCollectsAllViolations(): void
    {
        $this->expectException(InvalidTableBuilderException::class);

        try {
            TableBuilder::for('users', $this->rows)
                ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
                ->sort('missing_sort')
                ->filter('missing_filter')
                ->assembleTable(null);
        } catch (InvalidTableBuilderException $e) {
            $this->assertStringContainsString('missing_sort', $e->getMessage());
            $this->assertStringContainsString('missing_filter', $e->getMessage());

            throw $e;
        } catch (BindingResolutionException $e) {
        }
    }

    /**
     * @throws BindingResolutionException
     */
    public function testHtmlPartsUrlSetViaMethod(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->htmlPartsUrl('https://example.com/users/table')
            ->assembleTable(null);

        $this->assertSame('https://example.com/users/table', $table->htmlPartsUrl);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testHtmlPartsUrlNullByDefaultForArray(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->assembleTable(null);

        $this->assertNull($table->htmlPartsUrl);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testStickyHeader(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->stickyHeader()
            ->assembleTable(null);

        $this->assertTrue($table->stickyHeader);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testStickyHeaderFalseByDefault(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->assembleTable(null);

        $this->assertFalse($table->stickyHeader);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testExpandable(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->expandable('details')
            ->assembleTable(null);

        $this->assertSame('details', $table->expandable);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testExpandableNullByDefault(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->assembleTable(null);

        $this->assertNull($table->expandable);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testEmptyStateFromString(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->emptyState('No users found')
            ->assembleTable(null);

        $this->assertInstanceOf(EmptyState::class, $table->emptyState);
        $this->assertSame('No users found', $table->emptyState->title);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testEmptyStateFromObject(): void
    {
        $state = new EmptyState('No users found', 'Try adjusting your filters.', 'heroicon-o-users');

        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->emptyState($state)
            ->assembleTable(null);

        $this->assertSame($state, $table->emptyState);
        $this->assertSame('Try adjusting your filters.', $table->emptyState->description);
        $this->assertSame('heroicon-o-users', $table->emptyState->icon);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testEmptyStateNullByDefault(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->assembleTable(null);

        $this->assertNull($table->emptyState);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testBulkActionsBuiltCorrectly(): void
    {
        $table = TableBuilder::for('users', $this->rows)
            ->column('name', 'Name', fn (array $r) => Cell::simple($r['name']))
            ->checkboxes()
            ->bulkAction('Delete', '/delete', 'DELETE', Icon::heroicon('heroicon-o-trash'), Type::DANGER)
            ->assembleTable(null);

        $this->assertCount(1, $table->bulkActions);
        $this->assertSame('Delete', $table->bulkActions[0]->label);
        $this->assertSame('/delete', $table->bulkActions[0]->action);
        $this->assertSame('DELETE', $table->bulkActions[0]->method);
        $this->assertSame(Type::DANGER, $table->bulkActions[0]->type);
    }
}
