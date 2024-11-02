<?php

namespace Patrikjak\Utils\Table\View\Cells;

use Illuminate\Contracts\View\View;

final class Simple extends Cell
{
    public function render(): View
    {
        return view('pjutils::table.cells.simple');
    }
}