<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Table\Services\Implementations;

use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;
use Patrikjak\Utils\Table\Services\BaseTableProvider;
use Patrikjak\Utils\Table\Services\TableProviderInterface;

class TableProvider extends BaseTableProvider implements TableProviderInterface
{
    use TableProviderData;

    private string $tableId = 'table';

    /**
     * @var array<string>
     */
    private array $columns = ['id', 'name', 'email', 'link', 'created_at', 'updated_at'];

    private string $rowId = 'id';

    private bool $showOrder = false;

    private bool $showCheckboxes = false;

    /**
     * @var array<Item>
     */
    private array $actions = [];

    public function getTableId(): string
    {
        return $this->tableId;
    }

    /** @inheritDoc */
    public function getHeader(): ?array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'link' => 'Link',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->getTableData();
    }

    /**
     * @inheritDoc
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getRowId(): string
    {
        return $this->rowId;
    }

    public function showOrder(): bool
    {
        return $this->showOrder;
    }

    public function showCheckboxes(): bool
    {
        return $this->showCheckboxes;
    }

    /**
     * @inheritDoc
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    public function setTableId(string $tableId): void
    {
        $this->tableId = $tableId;
    }

    /**
     * @param array<string> $columns
     */
    public function setColumns(array $columns): void
    {
        $this->columns = $columns;
    }

    public function setRowId(string $rowId): void
    {
        $this->rowId = $rowId;
    }

    public function setShowOrder(bool $showOrder): void
    {
        $this->showOrder = $showOrder;
    }

    public function setShowCheckboxes(bool $showCheckboxes): void
    {
        $this->showCheckboxes = $showCheckboxes;
    }

    /**
     * @param array<Item> $actions
     */
    public function setActions(array $actions): void
    {
        $this->actions = $actions;
    }
}