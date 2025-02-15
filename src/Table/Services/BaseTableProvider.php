<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Services;

use Illuminate\Support\Facades\Blade;
use Patrikjak\Utils\Table\Dto\Parameters;
use Patrikjak\Utils\Table\Dto\Sort\Settings;
use Patrikjak\Utils\Table\Dto\Sort\SortCriteria;
use Patrikjak\Utils\Table\Dto\Table;
use Patrikjak\Utils\Table\View\Body;
use Patrikjak\Utils\Table\View\Head;
use Patrikjak\Utils\Table\View\Options;

abstract class BaseTableProvider implements TableProviderInterface, Sortable
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

    public function getTable(?Parameters $parameters = null): Table
    {
        $this->parameters = $parameters;

        return new Table(
            $this->getTableId(),
            $this->getHeader(),
            $this->getData(),
            $this->getColumns(),
            $this->getRowId(),
            $this->showCheckboxes(),
            $this->showOrder(),
            $this->getExpandable(),
            $this->getActions(),
            $this instanceof SupportsPagination ? $this->getPaginationSettings() : null,
            $this->getBulkActions(),
            $this->getHtmlPartsUrl(),
            $this->getSortSettings(),
        );
    }

    /**
     * @inheritdoc
     */
    public function getColumns(): array
    {
        $header = $this->getHeader();

        if ($header === null) {
            return [];
        }

        return array_keys($header);
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
        if (!$this->table->isSortable()) {
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
}