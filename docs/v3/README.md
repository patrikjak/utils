# V3 Migration Guide

V3 is a major release with breaking changes. This directory documents what changed and how to migrate.

## Breaking Changes

- [Icon system](icons/icons.md) — `Icon` enum and `IconValue` replaced by a single `Icon` class
- [Table builder](table-builder.md) — `BaseTableProvider` / `BasePaginatedTableProvider` replaced by a single fluent `TableBuilder`; `CellType` enum removed; cell types and filter strategies are now pluggable via registries
- [Search unified with filters](table-builder.md#search) — `Parameters::$searchQuery`, `SearchSettings`, and `FilterService::applySearch()` removed; search is now a `TextFilterCriteria` on the synthetic `__search` column flowing through the normal filter pipeline
