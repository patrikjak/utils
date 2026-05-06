# V3 Directory Structure

## Core Concepts

### DTO (Data Transfer Object)
A dumb data bag whose only job is to carry data across a boundary (e.g. HTTP layer → service → view). Values are arbitrary, no invariants enforced, no identity. Lives in `Dto/`.

### Value Object
Immutable, defined entirely by its values, carries no identity. May have domain behaviour (formatting, validation, `toArray`). Lives in `ValueObjects/`.

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
│   ├── Image.php                      # Immutable image value (src, alt, fileName)
│   ├── Filter/
│   │   ├── AbstractFilterCriteria.php # Abstract base: column + getType() + toArray()
│   │   ├── FilterCriteria.php         # Immutable collection of AbstractFilterCriteria
│   │   ├── DateFilterCriteria.php     # from/to with getFormattedFrom/To()
│   │   ├── JsonFilterCriteria.php     # jsonPath, value, filterType
│   │   ├── NumberFilterCriteria.php   # from/to floats
│   │   ├── SelectFilterCriteria.php   # single string value
│   │   └── TextFilterCriteria.php     # value + TextFilterType operator
│   └── Sort/
│       └── SortCriteria.php           # column + SortOrder with toArray()
│
├── Enums/
│   ├── Filter/
│   │   ├── FilterType.php             # TEXT | SELECT | DATE | NUMBER | JSON
│   │   ├── JsonFilterType.php
│   │   └── TextFilterType.php
│   ├── Sort/
│   │   └── SortOrder.php              # ASC | DESC
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
│       ├── FilterService.php
│       ├── PaginatorService.php
│       ├── SortService.php
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
├── Contracts/
│   ├── Cells/
│   │   ├── Cell.php                   # getType(): CellType
│   │   └── SupportsIcon.php           # getIcon(): ?Icon
│   ├── Filter/
│   │   ├── FilterDefinition.php       # getType(): FilterType
│   │   ├── NeedsData.php              # getDataUrl(): string
│   │   └── RangeData.php              # getMin()/getMax(): ?string
│   └── Pagination/
│       ├── LinkItem.php               # getLabel/getUrl/isActive
│       └── Paginator.php              # extends Common\Contracts\Paginator
│
├── Dto/
│   ├── Table.php                      # Full table configuration bag (assembled once, passed to views)
│   ├── Parameters.php                 # HTTP request parameters for paginated/filtered tables
│   ├── Filter/
│   │   ├── Settings.php               # Array of FilterableColumns + active FilterCriteria
│   │   └── Definitions/
│   │       └── Select/
│   │           └── SelectFilterOptions.php  # View-layer DTO: renders Blade dropdown HTML
│   ├── Pagination/
│   │   ├── Settings.php               # Pagination config (page, pageSize, links)
│   │   └── Paginator.php              # Extends Common\Dto\Paginator; adds path/lastPage/links
│   ├── Search/
│   │   └── Settings.php               # Search config (searchable columns)
│   └── Sort/
│       └── Settings.php               # Array of SortableColumns + active SortCriteria
│
├── ValueObjects/
│   ├── ColumnVisibility.php           # Immutable; enforces "at least one column visible" invariant
│   ├── EmptyState.php                 # Immutable title+description+icon
│   ├── BulkActions/
│   │   └── Item.php                   # Immutable bulk action config VO
│   ├── Cells/
│   │   ├── Cell.php                   # Abstract base: value, maxLength, noTruncation, __toString
│   │   ├── Chip.php                   # Cell + Type enum
│   │   ├── Double.php                 # Cell + addition string
│   │   ├── Link.php                   # Cell + href string
│   │   ├── Simple.php                 # Cell + optional Icon
│   │   └── Actions/
│   │       └── Item.php               # Immutable action config (label, classId, icon, visibility, href)
│   ├── Filter/
│   │   └── Definitions/
│   │       ├── FilterableColumn.php   # label + column + FilterDefinition contract
│   │       ├── Date/
│   │       │   └── DateFilterDefinition.php    # from/to Carbon; implements RangeData
│   │       ├── Json/
│   │       │   └── JsonFilterDefinition.php    # optional jsonPath
│   │       ├── Number/
│   │       │   └── NumberFilterDefinition.php  # min/max float; implements RangeData
│   │       ├── Select/
│   │       │   ├── SelectFilterDefinition.php  # dataUrl; implements NeedsData
│   │       │   └── SelectFilterOption.php      # Immutable value+label pair
│   │       └── Text/
│   │           └── TextFilterDefinition.php    # No state; returns FilterType::TEXT
│   ├── Pagination/
│   │   └── LinkItem.php               # Immutable label+url+active; implements Contracts\Pagination\LinkItem
│   └── Sort/
│       └── SortableColumn.php         # Immutable label+column pair
│
├── Enums/
│   └── Cells/
│       └── CellType.php               # SIMPLE | LINK | CHIP | DOUBLE
│
├── Exceptions/
├── Factories/
├── Http/
├── Services/
│   ├── TableProviderInterface.php
│   ├── BaseTableProvider.php
│   ├── BasePaginatedTableProvider.php
│   ├── Filterable.php    (trait)
│   ├── Renderable.php    (trait)
│   ├── Searchable.php    (trait)
│   ├── Sortable.php      (trait)
│   └── SupportsPagination.php (trait)
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

---

## What Changed from V2

| V2 | V3 |
|---|---|
| `Common\Interfaces\Paginator` | `Common\Contracts\Paginator` |
| `Common\Dto\Image` | `Common\ValueObjects\Image` |
| `Common\Dto\Filter\*Criteria` | `Common\ValueObjects\Filter\*Criteria` |
| `Common\Dto\Sort\SortCriteria` | `Common\ValueObjects\Sort\SortCriteria` |
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
| `Table\Dto\Pagination\LinkItem` | `Table\ValueObjects\Pagination\LinkItem` |
| `Table\Dto\Sort\SortableColumn` | `Table\ValueObjects\Sort\SortableColumn` |
