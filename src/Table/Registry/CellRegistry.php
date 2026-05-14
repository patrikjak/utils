<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\Registry;

use Patrikjak\Utils\Table\Exceptions\UnregisteredCellTypeException;

final class CellRegistry
{
    /**
     * @var array<string, string>
     */
    private array $views = [];

    public function register(string $type, string $viewName): void
    {
        $this->views[$type] = $viewName;
    }

    public function getView(string $type): string
    {
        if (!isset($this->views[$type])) {
            throw UnregisteredCellTypeException::forType($type);
        }

        return $this->views[$type];
    }
}
