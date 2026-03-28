<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Services;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Common\Dto\Filter\FilterCriteria;
use Patrikjak\Utils\Common\Dto\Sort\SortCriteria;
use Patrikjak\Utils\Table\Dto\ColumnVisibility;
use Patrikjak\Utils\Table\Dto\EmptyState;
use Patrikjak\Utils\Table\Dto\Filter\Settings as FilterSettings;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Dto\Search\Settings as SearchSettings;
use Patrikjak\Utils\Table\Dto\Sort\Settings;
use Patrikjak\Utils\Table\Dto\Table;
use Patrikjak\Utils\Table\View\Body;
use Patrikjak\Utils\Table\View\Head;
use Patrikjak\Utils\Table\View\Options;

abstract class BaseTableProvider implements TableProviderInterface, Sortable, Filterable, Searchable
{
    protected ?Parameters $parameters;

    protected ?Table $table = null;

    /**
     * @inheritdoc
     */
    abstract public function getHeader(): ?array;

    /**
     * @inheritdoc
     */
    abstract public function getData(): array;

    /**
     * @throws BindingResolutionException
     */
    public function getTable(?Parameters $parameters = null): Table
    {
        $this->parameters = $parameters;

        $columnVisibility = $this->getColumnVisibility();
        [$header, $data] = $this->applyColumnVisibility($this->getHeader(), $this->getData(), $columnVisibility);

        return new Table(
            $this->getTableId(),
            $header,
            $data,
            array_keys($header ?? []),
            $this->getRowId(),
            $this->showCheckboxes(),
            $this->showOrder(),
            $this->getExpandable(),
            $this->getActions(),
            $this instanceof SupportsPagination ? $this->getPaginationSettings() : null,
            $this->getBulkActions(),
            $this->getHtmlPartsUrl(),
            $this->getSortSettings(),
            $this->getFilterSettings(),
            $this->getDefaultMaxLength(),
            $this->getSearchSettings(),
            $this->stickyHeader(),
            $this->getEmptyState(),
            $columnVisibility,
        );
    }

    /**
     * @inheritdoc
     */
    public function getColumns(): array
    {
        return array_keys($this->getHeader() ?? []);
    }

    public function getTableId(): string
    {
        return 'table';
    }

    public function getRowId(): string
    {
        return 'id';
    }

    public function showOrder(): bool
    {
        return false;
    }

    public function showCheckboxes(): bool
    {
        return false;
    }

    public function getExpandable(): ?string
    {
        return null;
    }

    public function stickyHeader(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getActions(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getBulkActions(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getSortableColumns(): array
    {
        return [];
    }

    public function getSortCriteria(): ?SortCriteria
    {
        return $this->parameters?->sortCriteria;
    }

    /**
     * @inheritDoc
     */
    public function getFilterableColumns(): array
    {
        return [];
    }

    public function getFilterCriteria(): ?FilterCriteria
    {
        return $this->parameters?->filterCriteria;
    }

    /**
     * @inheritDoc
     */
    public function getSearchableColumns(): array
    {
        return [];
    }

    public function getSearchQuery(): ?string
    {
        return $this->parameters?->searchQuery;
    }

    /**
     * @throws BindingResolutionException
     */
    public function getDefaultMaxLength(): ?int
    {
        $configured = app()->make(ConfigRepository::class)->get('pjutils.table.default_max_length');

        return $configured !== null ? (int) $configured : null;
    }

    /**
     * @inheritdoc
     */
    public function getHtmlParts(Parameters $parameters): array
    {
        $this->table = $this->getTable($parameters);

        return [
            'head' => $this->getHeadHtml(),
            'body' => $this->getBodyHtml(),
            'options' => $this->getOptionsHtml(),
        ];
    }

    public function getHtmlPartsUrl(): ?string
    {
        return null;
    }

    public function getEmptyState(): ?EmptyState
    {
        return null;
    }

    public function getColumnVisibility(): ?ColumnVisibility
    {
        return null;
    }

    protected function getHeadHtml(): string
    {
        return Blade::renderComponent(new Head($this->table));
    }

    protected function getBodyHtml(): string
    {
        return Blade::renderComponent(new Body($this->table));
    }

    protected function getOptionsHtml(): ?string
    {
        if (!$this->table->isFilterable() && !$this->table->isSearchable()) {
            return null;
        }

        return Blade::renderComponent(new Options($this->table));
    }

    private function getSortSettings(): ?Settings
    {
        if (count($this->getSortableColumns()) === 0) {
            return null;
        }

        return new Settings($this->getSortableColumns(), $this->parameters?->sortCriteria);
    }

    private function getFilterSettings(): ?FilterSettings
    {
        if (count($this->getFilterableColumns()) === 0) {
            return null;
        }

        return new FilterSettings($this->getFilterableColumns(), $this->parameters?->filterCriteria);
    }

    private function getSearchSettings(): ?SearchSettings
    {
        if (count($this->getSearchableColumns()) === 0) {
            return null;
        }

        return new SearchSettings($this->getSearchableColumns(), $this->parameters?->searchQuery);
    }

    /**
     * @param array<string, string>|null $header
     * @param array<array<scalar>> $data
     * @return array{array<string, string>, array<array<scalar>>}
     */
    private function applyColumnVisibility(?array $header, array $data, ?ColumnVisibility $columnVisibility): array
    {
        if ($header === null || $columnVisibility === null) {
            return [$header ?? [], $data];
        }

        $visibleKeys = $columnVisibility->getVisibleColumns($this->parameters?->visibleColumns);
        $rowId = $this->getRowId();

        $filteredHeader = array_filter(
            $header,
            static fn (string $key) => in_array($key, $visibleKeys, strict: true),
            ARRAY_FILTER_USE_KEY,
        );

        $filteredData = array_map(
            static function (array $row) use ($visibleKeys, $rowId): array {
                return array_filter(
                    $row,
                    static fn (string $key) => $key === $rowId || in_array($key, $visibleKeys, strict: true),
                    ARRAY_FILTER_USE_KEY,
                );
            },
            $data,
        );

        return [$filteredHeader, $filteredData];
    }
}
