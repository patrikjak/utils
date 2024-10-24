<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Table\Pagination\PaginationSettings;
use Patrikjak\Utils\Table\Parameters\TableParameters;
use Patrikjak\Utils\View\Components\Table\Body;
use Patrikjak\Utils\View\Components\Table\Pagination;

abstract class BasePaginatedTableProvider extends BaseTableProvider implements SupportPagination
{
    protected ?Table $table = null;

    private LengthAwarePaginator $paginator;

    final protected function getPageData(): Collection
    {
        $this->paginator = $this->getPaginator();

        return $this->paginator->getCollection();
    }

    abstract protected function getPaginator(): LengthAwarePaginator;

    /**
     * @inheritDoc
     */
    public function getHtmlParts(TableParameters $parameters): array
    {
        $this->table = $this->getTable($parameters);

        return [
            'body' => $this->getBodyHTML(),
            'pagination' => $this->getPaginationHTML(),
        ];
    }

    public function getPaginationSettings(): PaginationSettings
    {
        return new PaginationSettings(
            page: $this->parameters->page,
            pageSize: $this->parameters->pageSize,
            pageSizeOptions: $this->getPageSizeOptions(),
            path: $this->paginator->path(),
            links: $this->paginator->linkCollection(),
            lastPage: $this->paginator->lastPage(),
            isFirstPage: $this->parameters->page === 1,
            isLastPage: $this->parameters->page === $this->paginator->lastPage(),
        );
    }

    /**
     * @return array<int, int>
     */
    protected function getPageSizeOptions(): array
    {
        return [10 => 10, 20 => 20, 50 => 50, 100 => 100];
    }

    private function getBodyHTML(): string
    {
        return Blade::renderComponent(new Body($this->table));
    }

    private function getPaginationHTML(): string
    {
        return Blade::renderComponent(new Pagination($this->getPaginationSettings()));
    }
}