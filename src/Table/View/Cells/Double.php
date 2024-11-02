<?php

namespace Patrikjak\Utils\Table\View\Cells;

use Illuminate\Contracts\View\View;
use Patrikjak\Utils\Table\Dto\Cells\Cell as AbstractCell;
use Patrikjak\Utils\Table\Dto\Cells\Double as DoubleCell;

final class Double extends Cell
{
    public readonly string $addition;

    public function __construct(AbstractCell $cell, string $column)
    {
        parent::__construct($cell, $column);

        $this->addition = $this->getAddition();
    }

    public function render(): View
    {
        return view('pjutils::table.cells.double');
    }

    private function getAddition(): string
    {
        assert($this->cell instanceof DoubleCell);

        return $this->cell->addition;
    }
}