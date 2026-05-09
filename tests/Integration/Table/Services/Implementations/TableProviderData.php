<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Table\Services\Implementations;

use Illuminate\Pagination\LengthAwarePaginator;

trait TableProviderData
{
    /**
     * @return array<array<string, string>>
     */
    public function getRawTableData(): array
    {
        return [
            [
                'id' => '1',
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'link_label' => 'Test link',
                'link_href' => 'https://example.com',
                'created_at' => '2021-01-01 00:00:00',
                'updated_at' => '2021-01-01 00:00:00',
            ],
            [
                'id' => '2',
                'name' => 'Jane Doe',
                'email' => 'jane.doe@example.com',
                'link_label' => 'Test link',
                'link_href' => 'https://example.com',
                'created_at' => '2021-01-02 00:00:00',
                'updated_at' => '2021-01-02 00:00:00',
            ],
            [
                'id' => '3',
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'link_label' => 'Test link',
                'link_href' => 'https://example.com',
                'created_at' => '2021-01-03 00:00:00',
                'updated_at' => '2021-01-03 00:00:00',
            ],
            [
                'id' => '4',
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'link_label' => 'Test link',
                'link_href' => 'https://example.com',
                'created_at' => '2021-01-04 00:00:00',
                'updated_at' => '2021-01-04 00:00:00',
            ],
        ];
    }

    public function makePaginator(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $this->getRawTableData(),
            40,
            10,
            1,
            ['path' => 'https://example.com/table'],
        );
    }
}
