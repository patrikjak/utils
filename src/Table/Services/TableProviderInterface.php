<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\Services;

use Patrikjak\Utils\Table\Dtos\Action;
use Patrikjak\Utils\Table\Services\ColumnTypes\Interfaces\ColumnType;

interface TableProviderInterface
{
    public function getTableId(): string;

    /**
     * @return array<string, string>|null
     */
    public function getHeader(): ?array;

    /**
     * @return array<array<scalar>>
     */
    public function getData(): array;

    /**
     * @return array<string, ColumnType>
     */
    public function getColumns(): array;

    public function getRowId(): string;

    public function showOrder(): bool;

    public function showCheckboxes(): bool;

    public function getExpandable(): ?string;

    /**
     * @return array<Action>
     */
    public function getActions(): array;
}