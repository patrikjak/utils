<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table;

use Patrikjak\Utils\Table\Parameters\TableParameters;

abstract class BaseTableProvider implements TableProviderInterface
{
    protected TableParameters $parameters;

    final public function getTable(TableParameters $parameters): Table
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
            $this instanceof SupportPagination ? $this->getPaginationSettings() : null,
        );
    }

    /**
     * @inheritdoc
     */
    abstract public function getHeader(): ?array;

    /**
     * @inheritdoc
     */
    abstract public function getData(): array;

    /**
     * @inheritdoc
     */
    abstract public function getColumns(): array;

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
}