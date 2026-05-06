# Icon System

The `Icon` enum (`Common\Enums\Icon`) and `IconValue` value object (`Common\ValueObjects\IconValue`) have been removed and replaced by a single class: `Patrikjak\Utils\Common\Icon`.

## Named constructors

```php
use Patrikjak\Utils\Common\Icon;

Icon::heroicon('heroicon-o-trash')       // any Heroicon by its full name
Icon::svg('<svg>...</svg>')              // raw SVG string
Icon::storage('/absolute/path/to/file.svg') // reads an SVG from the filesystem
```

## Rendering

```php
$icon->toHtml()  // returns the SVG HTML string, safe to echo unescaped
```

## All icon properties are now `?Icon`

Every public API that previously accepted `Icon|string|null` or `IconValue|Icon|string|null` now accepts only `?Icon`. Pass an explicit `Icon` object or `null`.

Affected:
- `Table\Dto\Cells\Actions\Item` — `$icon` constructor param
- `Table\Dto\BulkActions\Item` — `$icon` constructor param
- `Table\Dto\Cells\Simple` — `$icon` constructor param
- `Table\Factories\Cells\CellFactory::simple()` — `$icon` param
- `Common\View\SummaryCard` — `:icon` Blade prop
- `Common\View\Form\Input` — `:icon` Blade prop

## Migration examples

### Table actions

```php
// Before
new Item('Edit', 'edit', Icon::EDIT)
new Item('Delete', 'delete', Icon::TRASH, Type::DANGER)

// After
new Item('Edit', 'edit', Icon::heroicon('heroicon-o-pencil-alt'))
new Item('Delete', 'delete', Icon::heroicon('heroicon-o-trash'), Type::DANGER)
```

### Bulk actions

```php
// Before
new BulkActionItem('Delete', '/delete', 'DELETE', Icon::TRASH, Type::DANGER)

// After
new BulkActionItem('Delete', '/delete', 'DELETE', Icon::heroicon('heroicon-o-trash'), Type::DANGER)
```

### Simple cell with icon

```php
// Before
CellFactory::simple('value', Icon::CHECK)

// After
CellFactory::simple('value', Icon::heroicon('heroicon-o-check'))
```

### Blade components

```blade
{{-- Before --}}
<x-pjutils::summary-card :icon="Icon::EYE" title="..." />
<x-pjutils::form.input :icon="Icon::CIRCLE_EXCLAMATION" ... />

{{-- After --}}
<x-pjutils::summary-card :icon="Icon::heroicon('heroicon-o-eye')" title="..." />
<x-pjutils::form.input :icon="Icon::heroicon('heroicon-o-exclamation-circle')" ... />
```

### `@icon` Blade directive

The directive now expects a full Heroicon name instead of an enum case name:

```blade
{{-- Before --}}
@icon('search')
@icon('filter')

{{-- After --}}
@icon('heroicon-o-search')
@icon('heroicon-o-filter')
```

### Custom icons

The `@customIcon` Blade directive is still available and unchanged. It loads an SVG file from `resources/views/icons/{name}.blade.php` in the consuming app:

```blade
@customIcon('my-icon')
{{-- loads resources/views/icons/my-icon.blade.php --}}
```

If you need to render a custom icon from PHP code directly, use `Icon::storage()`:

```php
// Before
Icon::getCustomAsHtml('my-icon')

// After
Icon::storage(resource_path('views/icons/my-icon.blade.php'))->toHtml()

// Or use Icon::svg() if you already have the SVG string
Icon::svg('<svg>...</svg>')->toHtml()
```

## Removed

| What | Replacement |
|------|-------------|
| `Icon` enum (`Common\Enums\Icon`) | `Icon::heroicon('heroicon-o-...')` |
| `IconValue` class (`Common\ValueObjects\IconValue`) | `Icon` class |
| `Icon::TRASH`, `Icon::EYE`, … (enum cases) | `Icon::heroicon('heroicon-o-trash')`, etc. |
| `IconValue::heroicon()` | `Icon::heroicon()` |
| `IconValue::fromEnum()` | not needed — no enum |
| `IconValue::wrap()` | not needed — pass `?Icon` directly |
| `cssClass()` method | removed — no per-icon CSS classes |
| Per-icon CSS classes (e.g. `eye-icon`, `trash-icon`) | removed from wrapper divs |

## Old enum case → heroicon name reference

| Old case | Heroicon name |
|----------|--------------|
| `Icon::CHECK` | `heroicon-o-check` |
| `Icon::WARNING` | `heroicon-o-exclamation` |
| `Icon::EDIT` | `heroicon-o-pencil-alt` |
| `Icon::TRASH` | `heroicon-o-trash` |
| `Icon::EYE` | `heroicon-o-eye` |
| `Icon::EYE_SLASH` | `heroicon-o-eye-off` |
| `Icon::CIRCLE_EXCLAMATION` | `heroicon-o-exclamation-circle` |
| `Icon::INFO` | `heroicon-o-information-circle` |
| `Icon::SORT` | `heroicon-o-switch-vertical` |
| `Icon::SORT_ASC` | `heroicon-o-sort-ascending` |
| `Icon::SORT_DESC` | `heroicon-o-sort-descending` |
| `Icon::FILTER` | `heroicon-o-filter` |
| `Icon::SEARCH` | `heroicon-o-search` |
