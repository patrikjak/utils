# V3 Directory Structure

## Core Concepts

### DTO (Data Transfer Object)
A dumb data bag whose only job is to carry data across a boundary (e.g. HTTP layer → service → view). Values are arbitrary, no invariants enforced, no identity. Lives in `Dto/`.

### Value Object
Immutable, defined entirely by its values, carries no identity. May have domain behaviour (formatting, validation, `toArray`). Lives in `ValueObjects/`. Declared as `readonly class` (without `final`) so consumers can extend while immutability is still enforced on all properties.

### Contract
PHP `interface` that defines a boundary between layers. Lives in `Contracts/`. Replaces the old `Interfaces/` directories.

---

## `src/Common/`

```
Common/
├── Contracts/
│   └── Paginator.php                  # Contract for paginator implementations
│
├── Dto/
│   └── Paginator.php                  # Generic page+pageSize+Collection bag
│
├── ValueObjects/
│   └── Image.php                      # Immutable image value (src, alt, fileName)
│
├── Enums/
│   ├── AlertType.php
│   ├── BadgeType.php
│   ├── ButtonSize.php
│   ├── ProgressType.php
│   ├── Type.php
│   ├── WidgetGridGap.php
│   ├── WidgetHeight.php
│   └── WidgetSize.php
│
├── Exceptions/
├── Helpers/
├── Http/
├── Rules/
├── Services/
│   └── QueryBuilder/
│       ├── FilterService.php          # Applies filter criteria to a query builder
│       ├── PaginatorService.php
│       ├── SortService.php            # Applies sort criteria to a query builder
│       └── Filters/
│           ├── Filter.php             # interface
│           ├── AbstractFilter.php
│           ├── JsonFilter.php
│           ├── RangeFilter.php
│           ├── SelectFilter.php
│           └── TextFilter.php
├── Traits/
│   └── EnumValues.php
└── View/                              # Blade component classes
```

---

## `src/Table/`

```
Table/
├── Builder/
│   ├── TableBuilder.php               # Fluent builder — entry point for assembling a Table DTO
│   ├── Cell.php                       # Static factory for cell value objects (Cell::simple, ::link, …)
│   ├── Filter.php                     # Static factory for filter definitions (Filter::text, ::select, …)
│   ├── ActionDefinition.php
│   ├── BulkActionDefinition.php
│   └── ColumnDefinition.php
│
├── Contracts/
│   ├── Cells/
│   │   ├── Cell.php                   # getType(): string
│   │   └── SupportsIcon.php           # getIcon(): ?Icon
│   ├── Filter/
│   │   ├── FilterCriteriaFactory.php  # make(column, data): ?AbstractFilterCriteria
│   │   ├── FilterDefinition.php       # getType(): string; getFilterData(): array
│   │   ├── NeedsData.php              # getDataUrl(): string
│   │   ├── NeedsDatabaseColumn.php    # getDatabaseColumn()/withDatabaseColumn(): static
│   │   └── RangeData.php              # getMin()/getMax(): ?string
│   └── Pagination/
│       ├── LinkItem.php               # getLabel/getUrl/isActive
│       └── Paginator.php              # extends Common\Contracts\Paginator
│
├── Dto/
│   ├── Table.php                      # Full table configuration bag (assembled once, passed to views)
│   ├── Parameters.php                 # HTTP request parameters for paginated/filtered/sorted tables
│   ├── Filter/
│   │   └── Settings.php               # Collection of FilterableColumns + active FilterCriteria
│   ├── Pagination/
│   │   ├── Settings.php               # Pagination config (page, pageSize, links)
│   │   └── Paginator.php              # Extends Common\Dto\Paginator; adds path/lastPage/links
│   ├── Search/
│   │   └── Settings.php               # Search config (searchable columns + query)
│   └── Sort/
│       └── Settings.php               # Collection of SortableColumns + active SortCriteria
│
├── Enums/
│   ├── Filter/
│   │   ├── FilterType.php             # Text | Select | Date | Number | Json
│   │   ├── JsonFilterType.php         # CONTAINS | NOT_CONTAINS | EQUALS | …
│   │   └── TextFilterType.php         # CONTAINS | NOT_CONTAINS | EQUALS | …
│   └── Sort/
│       └── SortOrder.php              # ASC | DESC
│
├── Exceptions/
│   ├── InvalidTableBuilderException.php
│   ├── UnregisteredCellTypeException.php
│   └── UnregisteredFilterStrategyException.php
│
├── Factories/
│   ├── Filter/
│   │   ├── Criteria/
│   │   │   ├── DateFilterCriteriaFactory.php
│   │   │   ├── JsonFilterCriteriaFactory.php
│   │   │   ├── NumberFilterCriteriaFactory.php
│   │   │   ├── SelectFilterCriteriaFactory.php
│   │   │   └── TextFilterCriteriaFactory.php
│   │   └── SelectFilterOptionsFactory.php
│   └── Pagination/
│       └── PaginatorFactory.php
│
├── Http/
│   ├── Controllers/
│   │   └── TableController.php        # Serves filter form HTML via GET /pjutils/table/filter-form/{type}
│   └── Requests/
│       ├── TableParametersRequest.php
│       └── Traits/
│           └── HandlesBulkActionsIds.php
│
├── Registry/
│   ├── CellRegistry.php               # Maps cell type string → Blade view name
│   └── FilterStrategyRegistry.php     # Maps filter type string → Filter strategy + factory + views
│
├── Services/
│   └── TableProvider.php              # Abstract base; implement build(?Parameters): TableBuilder
│
├── ValueObjects/
│   ├── ColumnVisibility.php           # Immutable; enforces "at least one column visible" invariant
│   ├── EmptyState.php                 # Immutable title+description+icon
│   ├── BulkActions/
│   │   └── Item.php                   # Immutable bulk action config VO
│   ├── Cells/
│   │   ├── Cell.php                   # Abstract base: value, maxLength, noTruncation, __toString
│   │   ├── Chip.php                   # Cell + Type enum
│   │   ├── TwoLine.php                # Cell + addition string
│   │   ├── Link.php                   # Cell + href string
│   │   ├── Simple.php                 # Cell + optional Icon
│   │   └── Actions/
│   │       └── Item.php               # Immutable action config (label, classId, icon, visibility, href)
│   ├── Filter/
│   │   ├── Criteria/
│   │   │   ├── AbstractFilterCriteria.php  # Abstract base: column + getType() + getFilterData()
│   │   │   ├── FilterCriteria.php          # Immutable collection of AbstractFilterCriteria
│   │   │   ├── DateFilterCriteria.php      # from/to Carbon properties
│   │   │   ├── JsonFilterCriteria.php      # jsonPath, value, filterType; implements NeedsDatabaseColumn
│   │   │   ├── NumberFilterCriteria.php    # from/to floats
│   │   │   ├── SelectFilterCriteria.php    # single string value
│   │   │   └── TextFilterCriteria.php      # value + TextFilterType operator
│   │   └── Definitions/
│   │       ├── FilterableColumn.php        # label + column + FilterDefinition + optional databaseColumn
│   │       ├── Custom/
│   │       │   └── CustomFilterDefinition.php  # Wraps an arbitrary registered type string
│   │       ├── Date/
│   │       │   └── DateFilterDefinition.php    # from/to Carbon; implements RangeData
│   │       ├── Json/
│   │       │   └── JsonFilterDefinition.php    # optional jsonPath
│   │       ├── Number/
│   │       │   └── NumberFilterDefinition.php  # min/max float; implements RangeData
│   │       ├── Select/
│   │       │   ├── SelectFilterDefinition.php  # dataUrl; implements NeedsData
│   │       │   ├── SelectFilterOption.php      # Immutable value+label pair
│   │       │   └── SelectFilterOptions.php     # Immutable; renders Blade dropdown via toArray()
│   │       └── Text/
│   │           └── TextFilterDefinition.php    # No state; returns FilterType::Text->value
│   ├── Pagination/
│   │   └── LinkItem.php               # Immutable label+url+active; implements Contracts\Pagination\LinkItem
│   └── Sort/
│       ├── SortCriteria.php           # column + SortOrder with toArray()
│       └── SortableColumn.php         # Immutable label+column pair
│
└── View/                              # Blade component classes
```

---

## Decision Rules

| Question | Answer |
|---|---|
| Does it carry arbitrary data across a layer boundary? | `Dto/` |
| Is it immutable, identity-less, defined entirely by its values? | `ValueObjects/` |
| Does it have domain behaviour (formatting, validation, type identity)? | `ValueObjects/` |
| Is it a PHP `interface`? | `Contracts/` |
| Is it a backed string/int enum? | `Enums/` |

## Class modifier rules

| Class kind | Modifier |
|---|---|
| Value Object (concrete) | `readonly class` |
| Value Object (abstract base) | `abstract readonly class` |
| DTO | `readonly class` |
| Contract | `interface` |
| Service / factory / view component | plain `class` |
| **`final`** | **Never** — consumers must be able to subclass anything |
| Hook methods in base classes | `protected`, not `private` |

---

## What Changed from V2

| V2 | V3 |
|---|---|
| `Common\Interfaces\Paginator` | `Common\Contracts\Paginator` |
| `Common\Dto\Image` | `Common\ValueObjects\Image` |
| `Common\Dto\Filter\*Criteria` | `Table\ValueObjects\Filter\Criteria\*Criteria` |
| `Common\Dto\Sort\SortCriteria` | `Table\ValueObjects\Sort\SortCriteria` |
| `Common\Enums\FilterType` | `Table\Enums\Filter\FilterType` |
| `Common\Enums\TextFilterType` | `Table\Enums\Filter\TextFilterType` |
| `Common\Enums\JsonFilterType` | `Table\Enums\Filter\JsonFilterType` |
| `Common\Enums\SortOrder` | `Table\Enums\Sort\SortOrder` |
| `Common\Contracts\Filter\FilterCriteriaFactory` | `Table\Contracts\Filter\FilterCriteriaFactory` |
| `Common\Contracts\Filter\NeedsDatabaseColumn` | `Table\Contracts\Filter\NeedsDatabaseColumn` |
| `Common\Factories\Filter\*FilterCriteriaFactory` | `Table\Factories\Filter\Criteria\*FilterCriteriaFactory` |
| `Common\Registry\FilterStrategyRegistry` | `Table\Registry\FilterStrategyRegistry` |
| `Common\Exceptions\UnregisteredFilterStrategyException` | `Table\Exceptions\UnregisteredFilterStrategyException` |
| `Table\Interfaces\Cells\*` | `Table\Contracts\Cells\*` |
| `Table\Interfaces\Pagination\*` | `Table\Contracts\Pagination\*` |
| `Table\Dto\Filter\Definitions\FilterDefinition` (interface) | `Table\Contracts\Filter\FilterDefinition` |
| `Table\Dto\Filter\Definitions\NeedsData` (interface) | `Table\Contracts\Filter\NeedsData` |
| `Table\Dto\Filter\Definitions\RangeData` (interface) | `Table\Contracts\Filter\RangeData` |
| `Table\Dto\ColumnVisibility` | `Table\ValueObjects\ColumnVisibility` |
| `Table\Dto\EmptyState` | `Table\ValueObjects\EmptyState` |
| `Table\Dto\BulkActions\Item` | `Table\ValueObjects\BulkActions\Item` |
| `Table\Dto\Cells\Cell` (+ Simple, Link, Chip, Double) | `Table\ValueObjects\Cells\*` |
| `Table\Dto\Cells\Actions\Item` | `Table\ValueObjects\Cells\Actions\Item` |
| `Table\Dto\Filter\Definitions\FilterableColumn` | `Table\ValueObjects\Filter\Definitions\FilterableColumn` |
| `Table\Dto\Filter\Definitions\*FilterDefinition` | `Table\ValueObjects\Filter\Definitions\*\*FilterDefinition` |
| `Table\Dto\Filter\Definitions\Select\SelectFilterOption` | `Table\ValueObjects\Filter\Definitions\Select\SelectFilterOption` |
| `Table\Dto\Filter\Definitions\Select\SelectFilterOptions` | `Table\ValueObjects\Filter\Definitions\Select\SelectFilterOptions` |
| `Table\Dto\Pagination\LinkItem` | `Table\ValueObjects\Pagination\LinkItem` |
| `Table\Dto\Sort\SortableColumn` | `Table\ValueObjects\Sort\SortableColumn` |
| `Table\Services\Sortable` | `Table\Contracts\Sortable` |
| `Table\Services\Filterable` | `Table\Contracts\Filterable` |
| `Table\Services\Searchable` | `Table\Contracts\Searchable` |
| `Table\Services\Renderable` | `Table\Contracts\Renderable` |
| `Table\Services\SupportsPagination` | `Table\Contracts\SupportsPagination` |

### Behavioural changes (same namespace, different modifier)

| Class | V2 | V3 |
|---|---|---|
| All VOs and DTOs | some `final`, some plain `class` | `readonly class` (no `final`) |
| `AbstractFilterCriteria` + cell `Cell` abstract base | plain `abstract class` | `abstract readonly class` |
| `BaseTableProvider` hook methods (`getSortSettings`, `getFilterSettings`, `getSearchSettings`, `applyColumnVisibility`) | `private` | `protected` |
| `BasePaginatedTableProvider::$paginator` | `private` | `protected` |
| `Form::resolveDataAttributes()` | `private` | `protected` |
| `Table\View\Cells\Cell::hasIcon()` | `private` | `protected` |
| All concrete classes | many were `final` | `final` removed; all are subclassable |

Subclasses of `Simple`, `Chip`, `Double` that re-promoted `$value`, `$maxLength`,
`$noTruncation` from the parent constructor must drop those promotions and pass
the values up via `parent::__construct()` instead (PHP readonly inheritance requirement).
