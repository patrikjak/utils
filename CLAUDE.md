# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

`patrikjak/utils` is a Laravel package (library) providing reusable UI components (tables, forms, modals, buttons, dropdowns, notifications) with both PHP backend (Blade components, DTOs, services) and frontend assets (SCSS, TypeScript). It is not a standalone app — it is installed into Laravel projects via Composer.

## Commands

### Tests
```bash
vendor/bin/phpunit                                    # all tests
vendor/bin/phpunit --testsuite Unit                   # unit tests only
vendor/bin/phpunit --testsuite Integration            # integration tests only
vendor/bin/phpunit --testsuite Feature                # feature tests only
vendor/bin/phpunit --filter TestClassName              # single test class
vendor/bin/phpunit --filter testMethodName             # single test method
```

### Linting & Static Analysis
```bash
vendor/bin/phpcs --standard=ruleset.xml               # code style (Slevomat coding standard)
vendor/bin/phpcbf --standard=ruleset.xml              # auto-fix code style
vendor/bin/phpstan analyse                            # static analysis (level 6)
```

### Frontend Assets
```bash
npm run build                                         # build CSS assets via Vite
npm run dev                                           # dev server with HMR
```

## Architecture

### Two Main Domains

**`src/Common/`** — Shared utilities used across the package:
- `View/` — Blade view components (Button, Form, Dropdown, Email, etc.) registered under `pjutils` namespace
- `Dto/` — Data transfer objects for filters (`AbstractFilterCriteria` subclasses), sorting (`SortCriteria`), pagination
- `Enums/` — `FilterType` (text/select/date/number), `Icon`, `Type`, `SortOrder`
- `Services/QueryBuilder/` — Database query helpers: `FilterService`, `SortService`, `PaginatorService`. Filter strategies implement `Filter` interface, resolved by `FilterType` in `FilterService`
- `Rules/` — Laravel validation rules (Password, TelephoneNumber)

**`src/Table/`** — Full-featured table system with sorting, filtering, pagination, and bulk actions:
- `Services/` — Core table providers. Extend `BaseTableProvider` for simple tables or `BasePaginatedTableProvider` for paginated ones
- `Dto/` — `Table` (the main DTO), `Parameters`, cell types, pagination/sort/filter settings
- `View/` — Blade components registered under `pjutils.table` namespace
- `Factories/` — `CellFactory` for creating cell types (Simple, Double, Chip, Link)
- `Http/` — `TableController` serves filter form HTML via `/pjutils/table/filter-form/{type}`

### Table Provider Pattern

The central pattern for creating tables:

1. Extend `BaseTableProvider` (or `BasePaginatedTableProvider` for pagination)
2. Implement `getHeader()` (column key => label map) and `getData()` (array of row data)
3. Override methods to enable features: `getSortableColumns()`, `getFilterableColumns()`, `getActions()`, `getBulkActions()`, `showCheckboxes()`, `showOrder()`
4. `getTable()` assembles the `Table` DTO; `getHtmlParts()` renders head/body/options (+ pagination) as HTML strings for AJAX refresh

`BasePaginatedTableProvider` requires `Parameters` (page, pageSize, sortCriteria, filterCriteria) and adds `getPaginator()` abstract method.

### Filter Strategy Pattern

`FilterService` applies filters to query builders. Each `FilterType` maps to a strategy:
- `TEXT` → `TextFilter`
- `SELECT` → `SelectFilter`
- `DATE`, `NUMBER` → `RangeFilter`

All implement `Filter` interface. Filters are grouped by column, with each group wrapped in a `where()` clause.

### Blade Component Namespaces

- `<x-pjutils-*>` — Common components (button, form, dropdown, etc.)
- `<x-pjutils.table-*>` — Table components

### Testing

- **Unit tests** (`tests/Unit/`) — Test DTOs, factories, services, rules in isolation
- **Integration tests** (`tests/Integration/`) — Use Orchestra Testbench to boot the package in a Laravel environment. Base class: `Tests\Integration\TestCase` (extends `OrchestraTestCase`, uses `MatchesSnapshots`)
- **Feature tests** (`tests/Feature/`) — Currently icon existence checks
- Snapshot testing via `spatie/phpunit-snapshot-assertions` — snapshots stored in `__snapshots__` directories

## Coding Conventions

- Every PHP file starts with `declare(strict_types = 1);` (note the spaces around `=`)
- Slevomat coding standard enforced via `ruleset.xml` — strict types, constructor property promotion, alphabetical use statements, trailing commas, no empty functions, max 300 lines per class
- DTOs are `final readonly class` with constructor promotion
- Enums are backed string enums
- PHP 8.4 required; uses modern features (property hooks, readonly, enums, match, named arguments)
- `$columnsMask` parameter pattern: maps display column names to actual DB column names in query services
