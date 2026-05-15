<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Builder;

use Closure;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Patrikjak\Utils\Common\Enums\Type;
use Patrikjak\Utils\Common\Icon;
use Patrikjak\Utils\Table\Contracts\Filter\FilterDefinition;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\FilterCriteria;
use Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\SearchFilterCriteria;
use Patrikjak\Utils\Table\Dto\Filter\Settings as FilterSettings;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as PaginatorDto;
use Patrikjak\Utils\Table\Dto\Pagination\Settings as PaginationSettings;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Dto\Sort\Settings as SortSettings;
use Patrikjak\Utils\Table\Dto\Table;
use Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\Text\TextFilterDefinition;
use Patrikjak\Utils\Table\Exceptions\InvalidTableBuilderException;
use Patrikjak\Utils\Table\Factories\Pagination\PaginatorFactory;
use Patrikjak\Utils\Table\ValueObjects\BulkActions\Item as BulkActionItem;
use Patrikjak\Utils\Table\ValueObjects\Cells\Actions\Item as ActionItem;
use Patrikjak\Utils\Table\ValueObjects\ColumnVisibility;
use Patrikjak\Utils\Table\ValueObjects\EmptyState;
use Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\FilterableColumn;
use Patrikjak\Utils\Table\ValueObjects\Sort\SortableColumn;

final class TableBuilder
{
    /**
     * @var array<string, ColumnDefinition>
     */
    private array $columns = [];

    /**
     * Keys are the column keys; values are always `true` (set semantics for O(1) dedup).
     *
     * @var array<string, true>
     */
    private array $sortKeys = [];

    /**
     * @var array<string, FilterDefinition>
     */
    private array $filterEntries = [];

    /**
     * Keys are the column keys; values are always `true` (set semantics for O(1) dedup).
     *
     * @var array<string, true>
     */
    private array $searchKeys = [];

    /**
     * @var array<ActionDefinition>
     */
    private array $actions = [];

    /**
     * @var array<BulkActionDefinition>
     */
    private array $bulkActions = [];

    private bool $showCheckboxes = false;

    private bool $showOrder = false;

    private string $rowId = 'id';

    private ?EmptyState $emptyState = null;

    private ?string $htmlPartsUrl = null;

    private ?string $expandable = null;

    private bool $stickyHeader = false;

    /**
     * @var array<int, int>
     */
    private array $pageSizeOptions = [10 => 10, 20 => 20, 50 => 50, 100 => 100];

    /**
     * @param LengthAwarePaginator<object>|Collection<int, object>|array<int, object|array<string, mixed>> $data
     * @param array<string, string> $columnMap
     */
    private function __construct(
        private readonly string $tableId,
        private readonly LengthAwarePaginator|Collection|array $data,
        private readonly array $columnMap = [],
    ) {
    }

    /**
     * @param LengthAwarePaginator<object>|Collection<int, object>|array<int, object|array<string, mixed>> $data
     * @param array<string, string> $columnMap [displayKey => realDatabaseColumn]
     */
    public static function for(
        string $tableId,
        LengthAwarePaginator|Collection|array $data,
        array $columnMap = [],
    ): self {
        return new self($tableId, $data, $columnMap);
    }

    public function column(
        string $key,
        string $label,
        Closure $render,
        bool $hidden = false,
    ): self {
        $this->columns[$key] = new ColumnDefinition(
            $key,
            $label,
            $render,
            $this->columnMap[$key] ?? null,
            $hidden,
        );

        return $this;
    }

    public function sort(string ...$keys): self
    {
        foreach ($keys as $key) {
            $this->sortKeys[$key] = true;
        }

        return $this;
    }

    public function filter(string $key, ?FilterDefinition $definition = null): self
    {
        $this->filterEntries[$key] = $definition ?? Filter::text();

        return $this;
    }

    public function search(string ...$keys): self
    {
        foreach ($keys as $key) {
            $this->searchKeys[$key] = true;
        }

        return $this;
    }

    public function action(
        string $label,
        string $classId,
        ?Icon $icon = null,
        ?Closure $href = null,
        Type $type = Type::NEUTRAL,
        string $method = 'GET',
        ?Closure $when = null,
        ?Closure $whenNot = null,
        bool $inline = false,
    ): self {
        $this->actions[] = new ActionDefinition(
            $label,
            $classId,
            $icon,
            $href ?? static fn () => '#',
            $type,
            $method,
            $when,
            $whenNot,
            $inline,
        );

        return $this;
    }

    public function bulkAction(
        string $label,
        string $action,
        string $method = 'POST',
        ?Icon $icon = null,
        Type $type = Type::NEUTRAL,
    ): self {
        $this->bulkActions[] = new BulkActionDefinition($label, $action, $method, $icon, $type);

        return $this;
    }

    public function checkboxes(): self
    {
        $this->showCheckboxes = true;

        return $this;
    }

    public function order(): self
    {
        $this->showOrder = true;

        return $this;
    }

    public function rowId(string $rowId): self
    {
        $this->rowId = $rowId;

        return $this;
    }

    /** Passing a plain string sets only the title; pass EmptyState for description and icon too. */
    public function emptyState(string|EmptyState $emptyState): self
    {
        $this->emptyState = $emptyState instanceof EmptyState
            ? $emptyState
            : new EmptyState($emptyState);

        return $this;
    }

    public function htmlPartsUrl(string $url): self
    {
        $this->htmlPartsUrl = $url;

        return $this;
    }

    public function expandable(string $expandable): self
    {
        $this->expandable = $expandable;

        return $this;
    }

    public function stickyHeader(): self
    {
        $this->stickyHeader = true;

        return $this;
    }

    /**
     * @param array<int, int> $options
     */
    public function pageSizeOptions(array $options): self
    {
        $this->pageSizeOptions = $options;

        return $this;
    }

    /**
     * @throws InvalidTableBuilderException
     * @throws BindingResolutionException
     */
    public function assembleTable(?Parameters $parameters): Table
    {
        $this->validate();

        $columnVisibility = $this->buildColumnVisibility();

        $visibleKeys = $columnVisibility !== null
            ? $columnVisibility->getVisibleColumns($parameters?->visibleColumns)
            : new Collection($this->columns)
                ->filter(static fn (ColumnDefinition $column) => !$column->hidden)
                ->keys()
                ->all();

        $header = new Collection($this->columns)
            ->only($visibleKeys)
            ->map(static fn (ColumnDefinition $column) => $column->label);

        [$data, $rawData] = $this->buildRows($visibleKeys);

        $filterSettings = $this->buildFilterSettings($parameters)?->withResolvedDatabaseColumns();

        return new Table(
            $this->tableId,
            $header,
            new Collection($data),
            new Collection($rawData),
            new Collection($visibleKeys),
            $this->rowId,
            $this->showCheckboxes,
            $this->showOrder,
            $this->expandable,
            $this->buildActionItems(),
            $this->buildPaginationSettings($parameters),
            $this->buildBulkActionItems(),
            $this->resolveHtmlPartsUrl(),
            $this->buildSortSettings($parameters),
            $filterSettings,
            $this->resolveDefaultMaxLength(),
            $this->stickyHeader,
            $this->emptyState,
            $columnVisibility,
            $this->buildEnrichedParameters($parameters, $filterSettings),
        );
    }

    /**
     * @param array<string> $visibleKeys
     * @return array{array<array<string, mixed>>, array<string, object|array<string, mixed>>}
     */
    private function buildRows(array $visibleKeys): array
    {
        $rawData = [];
        $data = [];

        foreach ($this->resolveRows() as $row) {
            $extracted = $this->extractRowId($row);

            if ($extracted === null) {
                throw InvalidTableBuilderException::forMissingRowId($this->rowId);
            }

            $rowId = (string) $extracted;
            $rawData[$rowId] = $row;

            $result = [$this->rowId => $rowId];

            foreach ($visibleKeys as $key) {
                $result[$key] = ($this->columns[$key]->render)($row);
            }

            $data[] = $result;
        }

        return [$data, $rawData];
    }

    private function buildEnrichedParameters(?Parameters $parameters, ?FilterSettings $filterSettings): ?Parameters
    {
        if ($parameters === null || $filterSettings === null) {
            return $parameters;
        }

        return $parameters->withFilterCriteria($filterSettings->criteria);
    }

    private function validate(): void
    {
        $violations = [];
        $columnKeys = array_keys($this->columns);

        if ($this->columns === []) {
            $violations[] = 'at least one column() must be defined';
        }

        foreach (array_keys($this->sortKeys) as $key) {
            if (!in_array($key, $columnKeys, true)) {
                $violations[] = sprintf('sort key "%s" does not match any column', $key);
            }
        }

        foreach (array_keys($this->filterEntries) as $key) {
            if (!in_array($key, $columnKeys, true)) {
                $violations[] = sprintf('filter key "%s" does not match any column', $key);
            }
        }

        foreach (array_keys($this->searchKeys) as $key) {
            if (!in_array($key, $columnKeys, true)) {
                $violations[] = sprintf('search key "%s" does not match any column', $key);
            }
        }

        if ($this->bulkActions !== [] && !$this->showCheckboxes) {
            $violations[] = 'bulkAction() requires checkboxes() to be called';
        }

        if ($violations !== []) {
            throw InvalidTableBuilderException::withViolations($violations);
        }
    }


    /**
     * @return array<int, object|array<string, mixed>>
     */
    private function resolveRows(): array
    {
        if ($this->data instanceof LengthAwarePaginator) {
            return $this->data->items();
        }

        if ($this->data instanceof Collection) {
            return $this->data->all();
        }

        return $this->data;
    }

    /**
     * @param object|array<string, mixed> $row
     */
    private function extractRowId(object|array $row): string|int|null
    {
        if (is_array($row)) {
            return $row[$this->rowId] ?? null;
        }

        return $row->{$this->rowId} ?? null;
    }

    private function buildSortSettings(?Parameters $parameters): ?SortSettings
    {
        if ($this->sortKeys === []) {
            return null;
        }

        $sortableColumns = new Collection(array_keys($this->sortKeys))->map(function (string $key): SortableColumn {
            $column = $this->columns[$key];

            return new SortableColumn($column->label, $key, $column->resolvedDatabaseColumn ?? $key);
        });

        return new SortSettings($sortableColumns, $parameters?->sortCriteria);
    }

    private function buildFilterSettings(?Parameters $parameters): ?FilterSettings
    {
        if ($this->filterEntries === [] && $this->searchKeys === []) {
            return null;
        }

        $filterableColumns = new Collection($this->filterEntries)
            ->map(function (FilterDefinition $definition, string $key): FilterableColumn {
                $column = $this->columns[$key];

                return new FilterableColumn($column->label, $key, $definition, $column->resolvedDatabaseColumn);
            })
            ->values();

        if ($this->searchKeys !== []) {
            $filterableColumns->push(new FilterableColumn('', SearchFilterCriteria::COLUMN, new TextFilterDefinition()));
        }

        $filterCriteria = $this->enrichSearchCriteria($parameters?->filterCriteria);

        return new FilterSettings($filterableColumns, $filterCriteria);
    }

    private function enrichSearchCriteria(?FilterCriteria $filterCriteria): ?FilterCriteria
    {
        if ($filterCriteria === null || $this->searchKeys === []) {
            return $filterCriteria;
        }

        $resolvedColumns = array_map(
            fn (string $key): string => $this->columns[$key]->resolvedDatabaseColumn ?? $key,
            array_keys($this->searchKeys),
        );

        $enriched = array_map(
            static function (mixed $filter) use ($resolvedColumns): mixed {
                if (!$filter instanceof SearchFilterCriteria) {
                    return $filter;
                }

                return new SearchFilterCriteria($filter->value ?? '', $resolvedColumns);
            },
            $filterCriteria->filters,
        );

        return new FilterCriteria($enriched);
    }

    private function buildPaginationSettings(?Parameters $parameters): ?PaginationSettings
    {
        if (!($this->data instanceof LengthAwarePaginator)) {
            return null;
        }

        $paginator = $this->resolvePaginator();

        return new PaginationSettings(
            $parameters !== null ? $parameters->page : $this->data->currentPage(),
            $parameters !== null ? $parameters->pageSize : $this->data->perPage(),
            $this->pageSizeOptions,
            $paginator->getPath(),
            $paginator->getLinks(),
            $paginator->getLastPage(),
        );
    }

    /**
     * @return Collection<int, ActionItem>
     */
    private function buildActionItems(): Collection
    {
        return new Collection(array_map(function (ActionDefinition $definition): ActionItem {
            if ($definition->when !== null) {
                $visible = $definition->when;
            } elseif ($definition->whenNot !== null) {
                $whenNot = $definition->whenNot;
                $visible = static fn (mixed $row) => !$whenNot($row);
            }

            return new ActionItem(
                $definition->label,
                $definition->classId,
                $definition->icon,
                $definition->type,
                $visible ?? true,
                $definition->href,
                $definition->method !== 'GET' ? $definition->method : null,
                $definition->inline,
            );
        }, $this->actions));
    }

    /**
     * @return Collection<int, BulkActionItem>
     */
    private function buildBulkActionItems(): Collection
    {
        return new Collection(array_map(
            static fn (BulkActionDefinition $definition) => new BulkActionItem(
                $definition->label,
                $definition->action,
                $definition->method,
                $definition->icon,
                $definition->type,
            ),
            $this->bulkActions,
        ));
    }

    private function buildColumnVisibility(): ?ColumnVisibility
    {
        $columns = new Collection($this->columns);
        $hiddenKeys = $columns
            ->filter(static fn (ColumnDefinition $column) => $column->hidden)
            ->keys()
            ->all();

        if ($hiddenKeys === []) {
            return null;
        }

        $all = $columns->map(static fn (ColumnDefinition $column) => $column->label)->all();

        return new ColumnVisibility($all, $hiddenKeys);
    }

    private function resolvePaginator(): PaginatorDto
    {
        assert($this->data instanceof LengthAwarePaginator);

        return PaginatorFactory::createFromLengthAwarePaginator($this->data);
    }

    private function resolveHtmlPartsUrl(): ?string
    {
        if ($this->htmlPartsUrl !== null) {
            return $this->htmlPartsUrl;
        }

        if ($this->data instanceof LengthAwarePaginator) {
            return $this->data->path();
        }

        return null;
    }

    /**
     * @throws BindingResolutionException
     */
    private function resolveDefaultMaxLength(): ?int
    {
        $configured = app()->make(ConfigRepository::class)->get('pjutils.table.default_max_length');

        return $configured !== null ? (int) $configured : null;
    }
}
