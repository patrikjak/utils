# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- **Full table search** — `applySearch()` on `FilterService` applies a `LIKE` search across multiple columns with proper escaping of `%`, `_`, and `\` characters
- **`Searchable` interface** — `src/Table/Services/Searchable.php` extends `Renderable`; declares `getSearchableColumns(): array` and `getSearchQuery(): ?string`; `BaseTableProvider` now implements it with default empty implementations

### Fixed

- **`applySearch()` column mask lookup** — `FilterService::applySearch()` now correctly resolves columns through the `$columnsMask` using the same `[realDbColumn => displayColumn]` convention as `applyFilter()` and `SortService::applySort()`

## [2.11.0] - 2026-03-05

### Added

- Tooltip for long cells (#44)

### Changed

- CI configuration for linting and testing workflows (#43)

## [2.10.0] - 2026-03-01

### Added

- Loading state while table data is being fetched (#42)

## [2.9.0] - 2026-02-28

### Added

- JSON filter support for querying JSON column fields via path expressions (#40)
- Release workflow configuration (#41)

## [2.8.2] - 2025-04-25

### Fixed

- Form handles async additional data properly (#37)

## [2.8.1] - 2025-04-20

### Fixed

- Refresh table after action click (#36)

## [2.8.0] - 2025-04-20

### Added

- Table action rendered as a link (#35)

## [2.7.0] - 2025-04-19

### Added

- Improved table actions (#34)

## [2.6.1] - 2025-04-18

### Fixed

- `newFiles()` method on file uploader (#33)

## [2.6.0] - 2025-04-18

### Added

- Photo file uploader component (#32)

## [2.5.1] - 2025-03-27

### Fixed

- Filter modal URL resolution (#31)

## [2.5.0] - 2025-03-23

### Added

- Link cell type for table (#29)

## [2.4.0] - 2025-03-15

### Added

- International telephone number input support (#28)

## [2.3.0] - 2025-03-08

### Added

- `InstallCommand` for package setup via `php artisan pjutils:install`

## [2.2.1] - 2025-03-06

### Fixed

- Hidden input handling
- Security vulnerability patch (#26)

## [2.2.0] - 2025-03-01

### Changed

- Updated to Laravel 12 and PHP 8.4 (#25)

## [2.1.0] - 2025-02-27

### Changed

- Style improvements (#24)

## [2.0.1] - 2025-02-25

### Fixed

- Dropdown rendering inside filter modal (#23)

## [2.0.0] - 2025-02-25

### Added

- Sort and filter support for tables (#21)
- Bulk actions (#20)

## [1.5.0] - 2025-01-25

### Added

- Bulk actions — frontend and backend implementation with tests

## [1.4.5] - 2025-01-19

### Fixed

- Removed unnecessary HTML attributes (#19)

## [1.4.4] - 2025-01-18

### Fixed

- Carbon vulnerability patch

## [1.4.3] - 2025-01-18

### Fixed

- Vendor groups configuration

## [1.4.2] - 2025-01-12

### Fixed

- Property name typo (#18)

## [1.4.1] - 2025-01-12

### Fixed

- Form attributes (#17)

## [1.4.0] - 2024-12-24

### Added

- Email layout Blade components (`Layout`, `Header`, `Footer`) (#15, #16)

## [1.3.0] - 2024-12-15

### Added

- `ValidationException` trait for request handling (#14)

## [1.2.1] - 2024-12-10

### Added

- `VerifyRecaptcha` middleware (#13)

## [1.1.1] - 2024-12-09

### Added

- `Password` and `TelephoneNumber` validation rules (#12)

## [1.0.1] - 2024-12-02

### Changed

- README updates (#11)

## [1.0.0] - 2024-12-01

### Added

- First stable release (#10)

## [0.3.1] - 2024-11-30

### Fixed

- Small fixes and improvements (#9)

## [0.3.0] - 2024-11-28

### Changed

- Removed icon color variants (#8)

## [0.2.3] - 2024-11-27

### Fixed

- Table fixes (#7)

## [0.2.2] - 2024-11-17

### Fixed

- Small fixes (#6)

## [0.2.1] - 2024-11-15

### Fixed

- Icons in form inputs (#5)

## [0.2.0] - 2024-11-10

### Added

- Table component with sorting, filtering, and pagination (#4)

## [0.1.1] - 2024-10-24

### Added

- MIT license (#2)

## [0.1.0] - 2024-10-24

### Added

- Initial release
