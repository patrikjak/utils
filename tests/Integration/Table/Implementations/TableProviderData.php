<?php

namespace Patrikjak\Utils\Tests\Integration\Table\Implementations;

use Patrikjak\Utils\Table\Services\Cell;

trait TableProviderData
{
    public function getTableData(): array
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
}