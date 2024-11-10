<?php

namespace Patrikjak\Utils\Tests\Integration\Table\Implementations;

use Patrikjak\Utils\Table\Services\BaseTableProvider;
use Patrikjak\Utils\Table\Services\Cell;
use Patrikjak\Utils\Table\Services\TableProviderInterface;

class TableProvider extends BaseTableProvider implements TableProviderInterface
{
    private string $tableId = 'table';

    private array $columns = ['id', 'name', 'email', 'created_at', 'updated_at'];

    private string $rowId = 'id';

    private bool $showOrder = false;

    private bool $showCheckboxes = false;

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
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ];
    }

    /** @inheritDoc */
    public function getData(): array
    {
        return [
            [
                'id' => Cell::simple('1'),
                'name' => Cell::simple('John Doe'),
                'email' => Cell::simple('john.doe@example.com'),
                'created_at' => Cell::simple('2021-01-01 00:00:00'),
                'updated_at' => Cell::simple('2021-01-01 00:00:00'),
            ],
            [
                'id' => Cell::simple('2'),
                'name' => Cell::simple('Jane Doe'),
                'email' => Cell::simple('jane.doe@example.com'),
                'created_at' => Cell::simple('2021-01-02 00:00:00'),
                'updated_at' => Cell::simple('2021-01-02 00:00:00'),
            ],
            [
                'id' => Cell::simple('3'),
                'name' => Cell::simple('John Smith'),
                'email' => Cell::simple('john.smith@example.com'),
                'created_at' => Cell::simple('2021-01-03 00:00:00'),
                'updated_at' => Cell::simple('2021-01-03 00:00:00'),
            ],
            [
                'id' => Cell::simple('4'),
                'name' => Cell::simple('Jane Smith'),
                'email' => Cell::simple('jane.smith@example.com'),
                'created_at' => Cell::simple('2021-01-04 00:00:00'),
                'updated_at' => Cell::simple('2021-01-04 00:00:00'),
            ],
        ];
    }

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

    public function getActions(): array
    {
        return $this->actions;
    }

    public function setTableId(string $tableId): void
    {
        $this->tableId = $tableId;
    }

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

    public function setActions(array $actions): void
    {
        $this->actions = $actions;
    }
}