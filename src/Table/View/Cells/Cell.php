<?php

namespace Patrikjak\Utils\Table\View\Cells;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Common\Enums\Icon;
use Patrikjak\Utils\Table\Dto\Cells\Cell as AbstractCell;
use Patrikjak\Utils\Table\Dto\Interfaces\Cells\SupportsIcon;

abstract class Cell extends Component
{
    public readonly string $cellClass;

    public ?Icon $icon = null;

    public function __construct(
        public AbstractCell $cell,
        public string $column,
    ) {
        $this->cellClass = $this->getCellClass();

        if ($this->hasIcon()) {
            assert($this->cell instanceof SupportsIcon);

            $this->icon = $this->cell->getIcon();
        }
    }

    abstract public function render(): View;

    public function getCellClass(): string
    {
        $classes = [$this->cell->getType()->value, $this->column];

        if ($this->hasIcon()) {
            $classes[] = 'with-icon';
        }

        return implode(' ', $classes);
    }

    private function hasIcon(): bool
    {
        return $this->cell instanceof SupportsIcon
            && $this->cell->getIcon() !== null
            && $this->cell->getIcon()->value !== '';
    }
}