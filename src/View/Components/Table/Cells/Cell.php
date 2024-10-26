<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components\Table\Cells;

use Illuminate\Contracts\View\View;
use Patrikjak\Utils\Table\Dtos\Table;
use Patrikjak\Utils\Table\Enums\ColumnTypes\IconType;
use Patrikjak\Utils\Table\Services\ColumnTypes\Interfaces\ColumnType;
use Patrikjak\Utils\Table\Services\ColumnTypes\Interfaces\SupportsIcon;
use Patrikjak\Utils\View\Components\Table\Body;

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
        public ColumnType $type,
    ) {
        parent::__construct($table);

        $this->cellClass = $this->resolveCellClass();
        $this->iconPath = $this->resolveIconPath();
    }

    public function render(): View
    {
        return view(sprintf('pjutils::components.table.cells.%s-cell', $this->type->getType()->value));
    }

    private function resolveCellClass(): string
    {
        $classes = [$this->type->getType()->value, $this->dataKey];

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

        assert($this->type instanceof SupportsIcon);

        if ($this->type->getIconType() === IconType::STATIC) {
            return $this->type->getIcon();
        }

        return $this->row[$this->type->getIcon()];
    }

    private function hasIcon(): bool
    {
        return $this->type instanceof SupportsIcon
            && $this->type->getIcon() !== ''
            && $this->type->getIcon() !== null;
    }
}