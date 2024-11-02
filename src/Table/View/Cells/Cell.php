<?php

namespace Patrikjak\Utils\Table\View\Cells;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Cells\Cell as AbstractCell;

abstract class Cell extends Component
{
    public readonly string $cellClass;

    public function __construct(
        public AbstractCell $cell,
        public string $column,
    ) {
        $this->cellClass = $this->getCellClass();
    }

    abstract public function render(): View;

    public function getCellClass(): string
    {
        return implode(' ', [$this->cell->getType()->value, $this->column]);
    }
}