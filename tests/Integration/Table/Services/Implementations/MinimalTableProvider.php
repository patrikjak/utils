<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Tests\Integration\Table\Services\Implementations;

use Patrikjak\Utils\Table\Services\BaseTableProvider;
use Patrikjak\Utils\Table\Services\TableProviderInterface;

class MinimalTableProvider extends BaseTableProvider implements TableProviderInterface
{
    use TableProviderData;

    /**
     * @inheritDoc
     */
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
}
