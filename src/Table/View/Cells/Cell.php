<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Cells;

use Illuminate\Contracts\View\View;
use Patrikjak\Utils\Table\Dto\Interfaces\ColumnType;
use Patrikjak\Utils\Table\Dto\Interfaces\SupportsIcon;
use Patrikjak\Utils\Table\Dto\Table;
use Patrikjak\Utils\Table\Enums\IconType;
use Patrikjak\Utils\Table\View\Body;

abstract class Cell extends Body
{
    public readonly string $cellClass;

    public readonly ?string $iconPath;

    /**
     * @param array<scalar> $row
     */
    public function __construct(
        public Table $table,
        public array $row,
        public string $dataKey,
        public ColumnType $columnType,
    ) {
        parent::__construct($table);

        $this->cellClass = $this->resolveCellClass();
        $this->iconPath = $this->resolveIconPath();
    }

    public function render(): View
    {
        return view(sprintf('pjutils::table.cells.%s-cell', $this->columnType->getType()->value));
    }

    private function resolveCellClass(): string
    {
        $classes = [$this->columnType->getType()->value, $this->dataKey];

        if ($this->hasIcon()) {
            $classes[] = 'with-icon';
        }

        return implode(' ', $classes);
    }

    private function resolveIconPath(): ?string
    {
        if (!$this->hasIcon()) {
            return null;
        }

        assert($this->columnType instanceof SupportsIcon);

        if ($this->columnType->getIconType() === IconType::STATIC) {
            return $this->columnType->getIcon();
        }

        return $this->row[$this->columnType->getIcon()];
    }

    private function hasIcon(): bool
    {
        return $this->columnType instanceof SupportsIcon
            && $this->columnType->getIcon() !== ''
            && $this->columnType->getIcon() !== null;
    }
}