# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

`patrikjak/utils` v2.10.0 is a Laravel package (library) providing reusable UI components (tables, forms, modals, buttons, dropdowns, notifications, photo uploaders, email layouts) with both PHP backend (Blade components, DTOs, services, middleware) and frontend assets (SCSS, TypeScript). It is not a standalone app — it is installed into Laravel projects via Composer.

## Commands

All commands must be run via Docker. The `cli` service runs PHP (8.5), and the `node` service runs Node (25).

### Tests
```bash
docker compose run --rm cli vendor/bin/phpunit                                    # all tests
docker compose run --rm cli vendor/bin/phpunit --testsuite Unit                   # unit tests only
docker compose run --rm cli vendor/bin/phpunit --testsuite Integration            # integration tests only
docker compose run --rm cli vendor/bin/phpunit --testsuite Feature                # feature tests only
docker compose run --rm cli vendor/bin/phpunit --filter TestClassName              # single test class
docker compose run --rm cli vendor/bin/phpunit --filter testMethodName             # single test method
```

### Linting & Static Analysis
```bash
docker compose run --rm cli vendor/bin/phpcs --standard=ruleset.xml               # code style (Slevomat)
docker compose run --rm cli vendor/bin/phpcbf --standard=ruleset.xml              # auto-fix code style
docker compose run --rm cli php -d memory_limit=2G vendor/bin/phpstan analyse     # static analysis (level 6)
```

### Frontend Assets
```bash
docker compose run --rm node npm run build                                         # build CSS/TS assets via Vite
docker compose run --rm node npm run dev                                           # dev server with HMR
```

## Architecture

### Two Main Domains

**`src/Common/`** — Shared utilities used across the package and consumers:

- `Console/Commands/` — `InstallCommand` for package setup
- `View/` — Blade components registered under `pjutils` namespace:
  - `Button`, `Form`, `Dropdown`, `Arrow`, `PhotoUploader`
  - `Dropdown/` — `Chosen`, `Item`
  - `Form/` — `Checkbox`, `File`, `Input`, `Radio`, `Select`, `Telephone`, `Textarea`
  - `FileUploader/` — `PhotoPreview`
  - `Email/` — `Layout`, `Header`, `Footer`
- `Dto/` — DTOs for filters, sorting, pagination, and media:
  - `Filter/` — `AbstractFilterCriteria`, `FilterCriteria`, `TextFilterCriteria`, `SelectFilterCriteria`, `DateFilterCriteria`, `NumberFilterCriteria`, `JsonFilterCriteria`
  - `Sort/` — `SortCriteria`
  - `Paginator`, `Image`
- `Enums/` — `Type`, `Icon`, `SortOrder`, `FilterType`, `JsonFilterType`, `TextFilterType`
- `Exceptions/` — `InvalidRecaptchaTokenException`
- `Helpers/` — `GrammaticalGender`
- `Http/Middlewares/` — `VerifyRecaptcha`
- `Http/Requests/Traits/` — `FileUpload`, `ValidationException`
- `Interfaces/` — `Paginator`
- `Rules/` — `Password`, `TelephoneNumber`
- `Services/` — `TelephonePattern`
- `Services/QueryBuilder/` — `FilterService`, `SortService`, `PaginatorService`
  - `Filters/` — `Filter` (interface), `AbstractFilter`, `TextFilter`, `SelectFilter`, `RangeFilter`, `JsonFilter`
- `Traits/` — `EnumValues`

**`src/Table/`** — Full-featured table system with sorting, filtering, pagination, and bulk actions:

- `Services/` — Core table providers and traits:
  - `BaseTableProvider` — extend for simple tables
  - `BasePaginatedTableProvider` — extend for paginated tables (requires `Parameters`)
  - `Filterable`, `Sortable`, `Renderable`, `SupportsPagination` (traits/interfaces)
  - `TableProviderInterface`
- `Dto/` — `Table` (main DTO), `Parameters`, cell types, pagination/sort/filter settings:
  - `Cells/` — `Cell`, `Simple`, `Double`, `Chip`, `Link`; `Actions/Item`; `BulkActions/Item`
  - `Filter/` — `Settings`; `Definitions/` — `FilterDefinition`, `FilterableColumn`, `NeedsData`, `RangeData`; sub-types: `DateFilterDefinition`, `JsonFilterDefinition`, `NumberFilterDefinition`, `SelectFilterDefinition` + `SelectFilterOption` + `SelectFilterOptions`, `TextFilterDefinition`
  - `Pagination/` — `Settings`, `Paginator`, `LinkItem`
  - `Sort/` — `Settings`, `SortableColumn`
- `Enums/` — `CellType`
- `Exceptions/` — `MissingTableParametersException`
- `Factories/` — `CellFactory`; `Filter/FilterableFactory`, `SelectFilterOptionsFactory`; `Pagination/PaginatorFactory`
- `Http/Controllers/` — `TableController` (serves filter form HTML via `/pjutils/table/filter-form/{type}`)
- `Http/Requests/` — `TableParametersRequest`; `Traits/HandlesBulkActionsIds`
- `Interfaces/` — `Cells/Cell`, `Cells/SupportsIcon`; `Pagination/LinkItem`, `Pagination/Paginator`
- `View/` — Blade components registered under `pjutils.table` namespace

### Table Provider Pattern

1. Extend `BaseTableProvider` (or `BasePaginatedTableProvider` for pagination)
2. Implement `getHeader()` (column key → label map) and `getData()` (array of row data)
3. Override methods to enable features: `getSortableColumns()`, `getFilterableColumns()`, `getActions()`, `getBulkActions()`, `showCheckboxes()`, `showOrder()`
4. `getTable()` assembles the `Table` DTO; `getHtmlParts()` renders head/body/options (+ pagination) as HTML strings for AJAX refresh

`BasePaginatedTableProvider` requires `Parameters` (page, pageSize, sortCriteria, filterCriteria) and adds `getPaginator()` abstract method.

### Filter Strategy Pattern

`FilterService` applies filters to query builders. Each `FilterType` maps to a strategy:
- `TEXT` → `TextFilter`
- `SELECT` → `SelectFilter`
- `DATE`, `NUMBER` → `RangeFilter`
- `JSON` → `JsonFilter`

All implement the `Filter` interface. Filters are grouped by column, each group wrapped in a `where()` clause.

Filter definitions for tables are expressed as `FilterDefinition` subtypes (`TextFilterDefinition`, `SelectFilterDefinition`, `DateFilterDefinition`, `NumberFilterDefinition`, `JsonFilterDefinition`), assembled into `FilterableColumn` DTOs.

### Blade Component Namespaces

- `<x-pjutils-*>` — Common components (button, form, dropdown, photo-uploader, etc.)
- `<x-pjutils.table-*>` — Table components

### Middleware

`VerifyRecaptcha` validates reCAPTCHA tokens on requests; throws `InvalidRecaptchaTokenException` on failure.

### Testing

- **Unit tests** (`tests/Unit/`) — Test DTOs, factories, services, rules in isolation
- **Integration tests** (`tests/Integration/`) — Orchestra Testbench; base class `Tests\Integration\TestCase` (extends `OrchestraTestCase`, uses `MatchesSnapshots`)
- **Feature tests** (`tests/Feature/`) — Route/HTTP-level checks
- Snapshot testing via `spatie/phpunit-snapshot-assertions` — snapshots in `__snapshots__/` directories next to tests
- Update snapshots: `docker compose run --rm cli vendor/bin/phpunit -d --update-snapshots`

## Coding Conventions

- Every PHP file starts with `declare(strict_types = 1);` (spaces around `=`)
- Slevomat coding standard enforced via `ruleset.xml` — strict types, constructor property promotion, alphabetical use statements, trailing commas, no empty functions, max 300 lines per class
- DTOs are `final readonly class` with constructor promotion
- Enums are backed string enums (`enum Foo: string`)
- PHP 8.4+ required; use modern features (property hooks, readonly, enums, match, named arguments, nullsafe operator)
- `$columnsMask` parameter pattern: maps display column names to actual DB column names in query services
- `EnumValues` trait available on enums for extracting backed values as arrays
