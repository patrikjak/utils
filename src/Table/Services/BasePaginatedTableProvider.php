<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Table\Dto\Pagination\Paginator as TablePaginator;
use Patrikjak\Utils\Table\Dto\Pagination\Settings;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Dto\Table;
use Patrikjak\Utils\Table\Exceptions\MissingTableParametersException;
use Patrikjak\Utils\Table\View\Pagination\Paginator;

abstract class BasePaginatedTableProvider extends BaseTableProvider implements
    TableProviderInterface,
    SupportsPagination
{
    private TablePaginator $paginator;

    abstract protected function getPaginator(): TablePaginator;

    public function getTable(?Parameters $parameters = null): Table
    {
        assert($parameters !== null, new MissingTableParametersException());

        return parent::getTable($parameters);
    }

    /**
     * @inheritDoc
     */
    public function getHtmlParts(Parameters $parameters): array
    {
        $baseParts = parent::getHtmlParts($parameters);

        return array_merge($baseParts, ['pagination' => $this->getPaginationHtml()]);
    }

    public function getPaginationSettings(): Settings
    {
        return new Settings(
            page: $this->parameters->page,
            pageSize: $this->parameters->pageSize,
            pageSizeOptions: $this->getPageSizeOptions(),
            path: $this->paginator->getPath(),
            links: $this->paginator->getLinks(),
            lastPage: $this->paginator->getLastPage(),
        );
    }

    public function getPageSize(): int
    {
        return $this->parameters->pageSize;
    }

    public function getCurrentPage(): int
    {
        return $this->parameters->page;
    }

    public function getHtmlPartsUrl(): ?string
    {
        return $this->paginator->getPath();
    }

    /**
     * @return array<int, int>
     */
    protected function getPageSizeOptions(): array
    {
        return [10 => 10, 20 => 20, 50 => 50, 100 => 100];
    }

    protected function getPageData(): Collection
    {
        $this->paginator = $this->getPaginator();

        return $this->paginator->getData();
    }

    protected function getPaginationHtml(): string
    {
        return Blade::renderComponent(new Paginator($this->getPaginationSettings()));
    }
}