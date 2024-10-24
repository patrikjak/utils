<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table;

use Patrikjak\Utils\Table\Actions\ActionInterface;
use Patrikjak\Utils\Table\ColumnTypes\Interfaces\ColumnType;

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
     * @return array<ActionInterface>
     */
    public function getActions(): array;
}