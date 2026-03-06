<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Table\Services\Implementations;

use Patrikjak\Utils\Table\Services\BaseTableProvider;
use Patrikjak\Utils\Table\Services\TableProviderInterface;

class FullTableSearchTableProvider extends BaseTableProvider implements TableProviderInterface
{
    use TableProviderData;

    /**
     * @var array<string>
     */
    private array $searchableColumns = [];

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

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return array_map(static function (array $user) {
            return [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'created_at' => $user['created_at'],
                'updated_at' => $user['updated_at'],
            ];
        }, $this->getTableData());
    }

    /**
     * @inheritDoc
     */
    public function getSearchableColumns(): array
    {
        return $this->searchableColumns;
    }

    /**
     * @param array<string> $columns
     */
    public function setSearchableColumns(array $columns): void
    {
        $this->searchableColumns = $columns;
    }
}
