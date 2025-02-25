<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Tests\Integration\Table\Implementations;

use Patrikjak\Utils\Table\Factories\Cells\CellFactory;

trait TableProviderData
{
    /**
     * @return array<array<string, mixed>>
     */
    public function getTableData(): array
    {
        return [
            [
                'id' => CellFactory::simple('1'),
                'name' => CellFactory::simple('John Doe'),
                'email' => CellFactory::simple('john.doe@example.com'),
                'created_at' => CellFactory::simple('2021-01-01 00:00:00'),
                'updated_at' => CellFactory::simple('2021-01-01 00:00:00'),
            ],
            [
                'id' => CellFactory::simple('2'),
                'name' => CellFactory::simple('Jane Doe'),
                'email' => CellFactory::simple('jane.doe@example.com'),
                'created_at' => CellFactory::simple('2021-01-02 00:00:00'),
                'updated_at' => CellFactory::simple('2021-01-02 00:00:00'),
            ],
            [
                'id' => CellFactory::simple('3'),
                'name' => CellFactory::simple('John Smith'),
                'email' => CellFactory::simple('john.smith@example.com'),
                'created_at' => CellFactory::simple('2021-01-03 00:00:00'),
                'updated_at' => CellFactory::simple('2021-01-03 00:00:00'),
            ],
            [
                'id' => CellFactory::simple('4'),
                'name' => CellFactory::simple('Jane Smith'),
                'email' => CellFactory::simple('jane.smith@example.com'),
                'created_at' => CellFactory::simple('2021-01-04 00:00:00'),
                'updated_at' => CellFactory::simple('2021-01-04 00:00:00'),
            ],
        ];
    }
}