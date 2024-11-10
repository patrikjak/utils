<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Cells;

use Illuminate\Contracts\View\View;
use Patrikjak\Utils\Table\Dto\Cells\Cell as AbstractCell;
use Patrikjak\Utils\Table\Dto\Cells\Chip as ChipCell;

final class Chip extends Cell
{
    public string $type;

    public function __construct(AbstractCell $cell, string $column)
    {
        parent::__construct($cell, $column);

        $this->type = $this->getType();
    }

    public function render(): View
    {
        return view('pjutils::table.cells.chip');
    }

    private function getType(): string
    {
        assert($this->cell instanceof ChipCell);

        return $this->cell->type->value;
    }
}