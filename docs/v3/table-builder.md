# Table Builder

The multi-method `BaseTableProvider` / `BasePaginatedTableProvider` pattern has been replaced by a single fluent `TableBuilder`. All table concerns — columns, sorting, filtering, search, actions, pagination — are expressed as chainable method calls on one object.

## Before / After

```php
// Before — two base classes, many overrideable methods
class UsersTableProvider extends BasePaginatedTableProvider
{
    public function getHeader(): array
    {
        return ['name' => 'Name', 'email' => 'Email'];
    }

    public function getData(): array
    {
        return $this->users->paginate($this->parameters)->map(fn ($u) => [
            'name'  => CellFactory::simple($u->name),
            'email' => CellFactory::link($u->email, "mailto:{$u->email}"),
        ])->all();
    }

    public function getSortableColumns(): array
    {
        return [new SortableColumn('Name', 'name'), new SortableColumn('Email', 'email')];
    }

    public function getActions(): array { ... }
    public function getPaginator(): LengthAwarePaginator { ... }
    // …
}

// After — one base class, one method
class UsersTableProvider extends TableProvider
{
    public function __construct(private readonly UserRepository $users) {}

    protected function build(?Parameters $parameters): TableBuilder
    {
        return TableBuilder::for('users', $this->users->paginate($parameters))
            ->column('name',  'Name',  fn (User $user) => Cell::simple($user->name))
            ->column('email', 'Email', fn (User $user) => Cell::link($user->email, "mailto:{$user->email}"))
            ->sort('name', 'email')
            ->action('Edit', 'edit', Icon::heroicon('heroicon-o-pencil-alt'),
                href: fn (User $user) => route('admin.users.edit', $user->id),
            );
    }
}
```

## `TableProvider` — the single base class

Extend `Patrikjak\Utils\Table\Services\TableProvider` and implement one method:

```php
abstract protected function build(?Parameters $parameters): TableBuilder;
```

The framework calls `build()` internally. You never call it yourself. `getTable()` and `getHtmlParts()` are still available for the same purposes as before.

### Table ID and controllers

`getTableId()` no longer exists on `TableProvider`. The table ID is now passed directly to `TableBuilder::for()` inside `build()`. Controllers that call `getTableParameters()` need the table ID string — expose it as a constant on the provider:

```php
class UsersTableProvider extends TableProvider
{
    public const string TABLE_ID = 'users-table';

    protected function build(?Parameters $parameters): TableBuilder
    {
        return TableBuilder::for(self::TABLE_ID, ...);
    }
}

// In the controller:
$provider->getTable($request->getTableParameters(UsersTableProvider::TABLE_ID));
$provider->getHtmlParts($request->getTableParameters(UsersTableProvider::TABLE_ID));
```

### Accessing sort / filter / search criteria

The old `getSortCriteria()`, `getFilterCriteria()`, `getSearchQuery()` methods are gone. Use `$parameters` directly:

```php
protected function build(?Parameters $parameters): TableBuilder
{
    $this->sortService->applySort($query, $parameters?->sortCriteria, $columnsMask);
    $this->filterService->applyFilter($query, $parameters?->filterCriteria, $columnsMask);
    $this->filterService->applySearch($query, $parameters?->searchQuery, $searchableColumns, $columnsMask);
}
```

## `TableBuilder` API

### Entry point

```php
TableBuilder::for(string $tableId, LengthAwarePaginator|Collection|array $data): self
```

Pass a `LengthAwarePaginator` for paginated tables — pagination settings are derived automatically. Pass a `Collection` or plain `array` for simple non-paginated tables.

### Columns

```php
->column(
    string $key,        // data key — must match what your render closure returns
    string $label,      // column header label
    Closure $render,    // receives one row item, returns a Cell VO
    ?string $databaseColumn = null, // DB column name if different from key (used for sort/filter)
    bool $hidden = false,
)
```

Hidden columns are excluded from the rendered table but remain available for column-visibility toggling. `ColumnVisibility` is derived automatically — you no longer construct it manually.

### Sorting

```php
->sort(string ...$keys)
```

Each key must match a column key defined with `->column()`. The `databaseColumn` value from the column definition is used as the actual DB column name for sort queries. Multiple keys can be passed at once or via chained calls.

### Filtering

```php
->filter(string $key, ?FilterDefinition $definition = null)
```

Defaults to `Filter::text()` when no definition is provided. Uses `Filter::*` static factory (see below). The `databaseColumn` value from the column definition is used as the DB column name.

### Search

```php
->search(string ...$keys)
```

Keys must match column keys. `databaseColumn` values are respected.

### Actions

```php
->action(
    string $label,
    string $classId,              // stable CSS class identifier, locale-independent (e.g. 'edit', 'delete')
    ?Icon $icon = null,
    ?Closure $href = null,        // receives original row item (model/array), returns string URL
    Type $type = Type::NEUTRAL,
    string $method = 'GET',
    ?Closure $when = null,        // receives original row item (model/array), returns bool
    ?Closure $whenNot = null,     // receives original row item (model/array), returns bool
    bool $inline = false,
)
```

Use `$when` to show an action only when a condition is true; use `$whenNot` to show it when a condition is false. Both default to always visible.

### Bulk actions

```php
->bulkAction(
    string $label,
    string $action,           // form action URL
    string $method = 'POST',
    ?Icon $icon = null,
    Type $type = Type::NEUTRAL,
)
```

Requires `->checkboxes()` to be called — the builder throws `InvalidTableBuilderException` at assembly time otherwise.

### Other options

```php
->checkboxes()                        // enable row checkboxes
->order()                             // show row order numbers
->rowId(string $rowId)                // row identifier key (default: 'id')
->emptyState(string|EmptyState $msg)  // message or VO shown when data is empty
->pageSizeOptions(array $options)     // e.g. [10 => 10, 25 => 25, 50 => 50]
```

## `Cell` — static factory

`Patrikjak\Utils\Table\Builder\Cell` replaces `CellFactory`.

```php
Cell::simple(string $value, ?Icon $icon = null, ?int $maxLength = null, bool $noTruncation = false)
Cell::twoLine(string $value, string $addition, ?int $maxLength = null, bool $noTruncation = false)
Cell::chip(string $value, Type $type = Type::NEUTRAL, ?int $maxLength = null, bool $noTruncation = false)
Cell::link(string $value, string $href, ?int $maxLength = null, bool $noTruncation = false)
```

## `CellType` — enum

`Patrikjak\Utils\Table\Enums\Cells\CellType` identifies the type of a rendered cell. Cases are PascalCase:

| Case | Backing value | Description |
|------|--------------|-------------|
| `CellType::Simple` | `'simple'` | Plain text, optionally with an icon |
| `CellType::TwoLine` | `'two-line'` | Primary value with a secondary line below |
| `CellType::Chip` | `'chip'` | Coloured chip badge |
| `CellType::Link` | `'link'` | Clickable anchor |

`getType(): CellType` is available on every cell value object returned by `Cell::*` factories. The backing string values are used by Blade views and are stable across renames.

## `Filter` — static factory

`Patrikjak\Utils\Table\Builder\Filter` replaces `FilterableFactory`.

```php
Filter::text()
Filter::select(string $dataProviderUrl)
Filter::date(?CarbonInterface $from = null, ?CarbonInterface $to = null)
Filter::number(?int $min = null, ?int $max = null)
Filter::json(?string $jsonPath = null)
```

## Validation

`assembleTable()` validates before producing the `Table` DTO. It collects all violations and throws one `InvalidTableBuilderException` listing them all:

- Sort key does not match any column key
- Filter key does not match any column key
- Search key does not match any column key
- `bulkAction()` called without `checkboxes()`

## Removed

| What | Replacement |
|------|-------------|
| `BaseTableProvider` | `TableProvider` |
| `BasePaginatedTableProvider` | `TableProvider` (auto-detects paginator) |
| `TableProviderInterface` | `TableProvider` |
| `CellFactory` | `Cell::` |
| `FilterableFactory` | `Filter::` |
| `MissingTableParametersException` | `InvalidTableBuilderException` |

## Full example

```php
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Common\Icon;
use Patrikjak\Utils\Table\Builder\Cell;
use Patrikjak\Utils\Table\Builder\Filter;
use Patrikjak\Utils\Table\Builder\TableBuilder;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Services\TableProvider;

class UsersTableProvider extends TableProvider
{
    public function __construct(private readonly UserRepository $users) {}

    protected function build(?Parameters $parameters): TableBuilder
    {
        return TableBuilder::for('users', $this->users->paginate($parameters))
            ->column('name',    'Name',    fn (User $user) => Cell::simple($user->name))
            ->column('email',   'Email',   fn (User $user) => Cell::link($user->email, "mailto:{$user->email}"))
            ->column('company', 'Company', fn (User $user) => Cell::simple($user->company_name), 'c.name')
            ->column('notes',   'Notes',   fn (User $user) => Cell::simple($user->notes), hidden: true)
            ->sort('name', 'email', 'company')
            ->filter('name')
            ->filter('company', Filter::select(route('api.companies')))
            ->search('name', 'email')
            ->action('Edit', 'edit',
                Icon::heroicon('heroicon-o-pencil-alt'),
                href: fn (User $user) => route('admin.users.edit', $user->id),
            )
            ->action('Delete', 'delete',
                Icon::heroicon('heroicon-o-trash'),
                href: fn (User $user) => route('admin.api.users.destroy', $user->id),
                type: Type::DANGER,
                method: 'DELETE',
                whenNot: fn (User $user) => $user->is_admin,
            )
            ->bulkAction('Delete selected', route('admin.api.users.bulk-destroy'),
                method: 'DELETE',
                icon: Icon::heroicon('heroicon-o-trash'),
                type: Type::DANGER,
            )
            ->checkboxes()
            ->rowId('uuid')
            ->emptyState(__('No users found'));
    }
}
```
