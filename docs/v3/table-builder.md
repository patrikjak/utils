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

    protected function build(?Parameters $parameters, array $columnMap): TableBuilder
    {
        return TableBuilder::for('users', $this->users->paginate($parameters), $columnMap)
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
abstract protected function build(?Parameters $parameters, array $columnMap): TableBuilder;
```

The framework calls `build()` internally — you never call it yourself. Always pass `$columnMap` through to `TableBuilder::for()` as the third argument. `getTable()` and `getHtmlParts()` are still the public API.

### Table ID and controllers

`getTableId()` no longer exists on `TableProvider`. The table ID is now passed directly to `TableBuilder::for()` inside `build()`. `getTableParameters()` no longer takes a table ID argument:

```php
class UsersTableProvider extends TableProvider
{
    protected function build(?Parameters $parameters, array $columnMap): TableBuilder
    {
        return TableBuilder::for('users-table', ..., $columnMap);
    }
}

// In the controller:
$provider->getTable($request->getTableParameters());
$provider->getHtmlParts($request->getTableParameters());
```

### Accessing sort / filter / search criteria

The old `getSortCriteria()`, `getFilterCriteria()`, `getSearchQuery()` methods are gone. Use `$parameters` directly:

```php
protected function build(?Parameters $parameters, array $columnMap): TableBuilder
{
    $this->sortService->applySort($query, $parameters?->sortCriteria, $columnMap);
    $this->filterService->applyFilter($query, $parameters?->filterCriteria, $columnMap);
    $this->filterService->applySearch($query, $parameters?->searchQuery, array_values($columnMap));
}
```

### Column mapping — `getColumnMap()`

When a display column key differs from its real DB column name (e.g. a joined column `c.name` displayed as `company`), declare the mapping once by overriding `getColumnMap()`. The base class forwards it to `build()` as `$columnMap` and it is passed to `TableBuilder::for()`, so every `->column()` call resolves its `databaseColumn` automatically — no need to set it on individual columns.

```php
class UsersTableProvider extends TableProvider
{
    protected function getColumnMap(): array
    {
        return [
            'company'    => 'c.name',
            'created_at' => 'users.created_at',
        ];
    }

    protected function build(?Parameters $parameters, array $columnMap): TableBuilder
    {
        $query = User::query()
            ->select('users.*', 'c.name AS company_name')
            ->join('companies AS c', 'users.company_id', '=', 'c.id');

        $this->filterService->applyFilter($query, $parameters?->filterCriteria, $columnMap);
        $this->sortService->applySort($query, $parameters?->sortCriteria, $columnMap);

        return TableBuilder::for('users', $paginator, $columnMap)
            ->column('company', 'Company', fn (User $u) => Cell::simple($u->company_name))
            ->sort('company')
            ->filter('company');
    }
}
```

The mapping keeps real DB column names out of the frontend: `$key` (e.g. `"company"`) is what appears in URLs and HTML; `$columnMap` resolves it to `"c.name"` server-side only.

### Applying sort / filter / search in controllers

If the query lives in a controller rather than inside `build()`, keep the query inside `build()` and return the data from there. Splitting the query across `build()` and a controller is not supported.

## `TableBuilder` API

### Entry point

```php
TableBuilder::for(string $tableId, LengthAwarePaginator|Collection|array $data, array $columnMap = []): self
```

Pass a `LengthAwarePaginator` for paginated tables — pagination settings are derived automatically. Pass a `Collection` or plain `array` for simple non-paginated tables. Always forward the `$columnMap` received from `build()` as the third argument.

### Columns

```php
->column(
    string $key,      // logical identifier — appears in URLs and HTML, never a real DB column name
    string $label,    // column header label
    Closure $render,  // receives one row item, returns a Cell VO
    bool $hidden = false,
)
```

If the real DB column name differs from `$key`, declare the mapping in `getColumnMap()` on the provider. The map is forwarded to `TableBuilder::for()` and resolved automatically for every `column()` call — no per-column configuration needed.

Hidden columns are excluded from the rendered table but remain available for column-visibility toggling. `ColumnVisibility` is derived automatically — you no longer construct it manually.

### Sorting

```php
->sort(string ...$keys)
```

Each key must match a column key defined with `->column()`. The real DB column name is resolved from the column map automatically. Multiple keys can be passed at once or via chained calls.

### Filtering

```php
->filter(string $key, ?FilterDefinition $definition = null)
```

Defaults to `Filter::text()` when no definition is provided. Uses `Filter::*` static factory (see below). The logical column `$key` is used as the filter identifier sent to the server. When the real DB column differs from the key, the builder resolves it automatically via the column map — use `$table->parameters` instead of the original request parameters when querying (see below).

### Search

```php
->search(string ...$keys)
```

Keys must match column keys. Real DB column names are resolved from the column map automatically.

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

## `Filter` — static factory

`Patrikjak\Utils\Table\Builder\Filter` replaces `FilterableFactory`.

```php
Filter::text()
Filter::select(string $dataProviderUrl)
Filter::date(?CarbonInterface $from = null, ?CarbonInterface $to = null)
Filter::number(?int $min = null, ?int $max = null)
Filter::json(?string $jsonPath = null)
Filter::registered(string $type)   // custom type key resolved from FilterStrategyRegistry
```

## Validation

`assembleTable()` validates before producing the `Table` DTO. It collects all violations and throws one `InvalidTableBuilderException` listing them all:

- Sort key does not match any column key
- Filter key does not match any column key
- Search key does not match any column key
- `bulkAction()` called without `checkboxes()`

## Pluggable cells and filter strategies

Built-in cell types and filter strategies are registered via singletons that consumers can extend.

### Custom cell views

`CellRegistry` maps a string type identifier to a Blade view name. Register a custom cell in your service provider's `boot()`:

```php
use Patrikjak\Utils\Table\Registry\CellRegistry;

public function boot(): void
{
    app(CellRegistry::class)->register('money', 'app.table.cells.money');
}
```

Your cell value object must implement `Contracts\Cells\Cell` and return the same type string from `getType()`:

```php
use Patrikjak\Utils\Table\Contracts\Cells\Cell as CellContract;
use Patrikjak\Utils\Table\ValueObjects\Cells\Cell;

readonly class MoneyCell extends Cell implements CellContract
{
    public function __construct(string $value, public string $currency) {
        parent::__construct($value);
    }

    public function getType(): string { return 'money'; }
}
```

Then use it in your `TableBuilder` column render closure:

```php
->column('price', 'Price', fn (Product $p) => new MoneyCell(number_format($p->price, 2), 'EUR'))
```

### Custom filter strategies

`FilterStrategyRegistry` maps a string type key to a `Filter` strategy **and** a `FilterCriteriaFactory`. Registering both together lets you add entirely new filter types that flow end-to-end: from HTTP request parsing through query building.

#### Overriding a built-in strategy

```php
use Patrikjak\Utils\Table\Factories\Filter\Criteria\TextFilterCriteriaFactory;
use Patrikjak\Utils\Table\Registry\FilterStrategyRegistry;
use Patrikjak\Utils\Table\Enums\Filter\FilterType;

public function boot(): void
{
    app(FilterStrategyRegistry::class)->register(
        FilterType::Text->value,
        new MyTextFilter(),
        new TextFilterCriteriaFactory(),
    );
}
```

#### Adding a fully custom filter type

1. **Criteria value object** — extend `AbstractFilterCriteria`. Implement the two abstract methods:
   - `getType(): string` — return your type key
   - `getFilterData(): array<string, scalar|null>` — key→value pairs serialized as `data-*` attributes on the active filter chip (read back by JS to reconstruct filter state on page reload)

```php
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;

readonly class RatingFilterCriteria extends AbstractFilterCriteria
{
    public function __construct(string $column, public int $min, public int $max)
    {
        parent::__construct($column);
    }

    public function getType(): string { return 'rating'; }

    public function getFilterData(): array
    {
        return ['min' => $this->min, 'max' => $this->max];
    }

    public function toArray(): array
    {
        return ['column' => $this->column, 'type' => $this->getType(), 'min' => $this->min, 'max' => $this->max];
    }
}
```

2. **Criteria factory** — implement `FilterCriteriaFactory` to parse the raw HTTP data:

```php
use Patrikjak\Utils\Table\Contracts\Filter\FilterCriteriaFactory;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;

final readonly class RatingFilterCriteriaFactory implements FilterCriteriaFactory
{
    public function make(string $column, array $data): ?AbstractFilterCriteria
    {
        if (!isset($data['min'], $data['max'])) {
            return null;
        }

        return new RatingFilterCriteria($column, (int) $data['min'], (int) $data['max']);
    }
}
```

3. **Filter strategy** — implement `Filter` to apply the criteria to a query:

```php
use Illuminate\Contracts\Database\Query\Builder;
use Patrikjak\Utils\Common\Services\QueryBuilder\Filters\Filter;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;

final readonly class RatingFilter implements Filter
{
    public function filter(Builder $query, AbstractFilterCriteria $criteria, array $columnsMask = []): void
    {
        assert($criteria instanceof RatingFilterCriteria);
        $query->whereBetween($criteria->column, [$criteria->min, $criteria->max]);
    }
}
```

3a. **`NeedsDatabaseColumn` — optional, for column-mapped filters** — if your filter queries a DB column whose name differs from the logical filter key (e.g. a JSON column, a qualified `table.column` name), implement `NeedsDatabaseColumn` on your criteria class. The builder will automatically resolve the correct DB column from the column map and make it available via `$table->parameters`:

```php
use Patrikjak\Utils\Table\Contracts\Filter\NeedsDatabaseColumn;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\AbstractFilterCriteria;

readonly class RatingFilterCriteria extends AbstractFilterCriteria implements NeedsDatabaseColumn
{
    public function __construct(
        string $column,
        public int $min,
        public int $max,
        public ?string $databaseColumn = null,
    ) {
        parent::__construct($column);
    }

    public function getDatabaseColumn(): ?string
    {
        return $this->databaseColumn;
    }

    public function withDatabaseColumn(string $databaseColumn): static
    {
        return new static($this->column, $this->min, $this->max, $databaseColumn);
    }

    // ...
}
```

Then use `$criteria->getDatabaseColumn() ?? $criteria->column` in your filter strategy:

```php
public function filter(Builder $query, AbstractFilterCriteria $criteria, array $columnsMask = []): void
{
    assert($criteria instanceof RatingFilterCriteria);
    $column = $criteria->getDatabaseColumn() ?? $criteria->column;
    $query->whereBetween($column, [$criteria->min, $criteria->max]);
}
```

4. **Filter modal form view** — a Blade partial rendered inside the filter modal. Elements that should be submitted must carry a `data-filter-field="<key>"` attribute matching the keys your `FilterCriteriaFactory::make()` expects. Dropdowns (`.pj-dropdown`) and standard inputs (`<input>`, `<select>`, `<textarea>`) are both supported:

```blade
{{-- resources/views/table/filter/forms/rating.blade.php --}}
<x-pjutils::form.input
    type="number"
    :label="__('Min rating')"
    name="rating_min"
    data-filter-field="min"
/>
<x-pjutils::form.input
    type="number"
    :label="__('Max rating')"
    name="rating_max"
    data-filter-field="max"
/>
```

5. **Chip view** — a Blade partial rendered inside the active-filter chip. It receives the `$option` variable (`FilterOption` VO with `->criteria` of your criteria type):

```blade
{{-- resources/views/table/filter/chips/rating.blade.php --}}
<span class="operator">:</span>
<span class="value">&nbsp;{{ $option->criteria->min }} – {{ $option->criteria->max }}</span>
```

6. **Register in your service provider** — pass the form view and chip view:

```php
use Patrikjak\Utils\Table\Registry\FilterStrategyRegistry;

public function boot(): void
{
    app(FilterStrategyRegistry::class)->register(
        'rating',
        new RatingFilter(),
        new RatingFilterCriteriaFactory(),
        formView: 'app.table.filter.forms.rating',
        chipView: 'app.table.filter.chips.rating',
    );
}
```

If `chipView` is omitted, no active-filter chip will be shown when that filter type is applied — the filter still works, it just won't be visible as a removable tag. Omit it only if you intentionally don't need chips for this filter type.

7. **Use in `TableBuilder`** with `Filter::registered()`:

```php
->filter('rating', Filter::registered('rating'))
```

The front-end sends `filter[column][0][type]=rating&filter[column][0][min]=3&filter[column][0][max]=5` and the registry handles the rest.

### Built-in type identifiers

| Cell type | String key | Filter type | String key |
|-----------|-----------|-------------|-----------|
| `Simple` | `'simple'` | `Text` | `'text'` |
| `TwoLine` | `'two-line'` | `Select` | `'select'` |
| `Chip` | `'chip'` | `Date` | `'date'` |
| `Link` | `'link'` | `Number` | `'number'` |
| | | `Json` | `'json'` |

## Removed

| What | Replacement |
|------|-------------|
| `BaseTableProvider` | `TableProvider` |
| `BasePaginatedTableProvider` | `TableProvider` (auto-detects paginator) |
| `TableProviderInterface` | `TableProvider` |
| `CellFactory` | `Cell::` |
| `FilterableFactory` | `Filter::` |
| `MissingTableParametersException` | `InvalidTableBuilderException` |
| `CellType` enum | `getType(): string` on cell value objects |
| `FilterType::TEXT` / `::SELECT` / `::DATE` / `::NUMBER` / `::JSON` | `FilterType::Text` / `::Select` / `::Date` / `::Number` / `::Json` |

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

    protected function getColumnMap(): array
    {
        return ['company' => 'c.name'];
    }

    protected function build(?Parameters $parameters, array $columnMap): TableBuilder
    {
        $paginator = $this->users->paginateWithCompany($parameters);

        return TableBuilder::for('users', $paginator, $columnMap)
            ->column('name',    'Name',    fn (User $user) => Cell::simple($user->name))
            ->column('email',   'Email',   fn (User $user) => Cell::link($user->email, "mailto:{$user->email}"))
            ->column('company', 'Company', fn (User $user) => Cell::simple($user->company_name))
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
