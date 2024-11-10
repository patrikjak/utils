<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Cells\Actions;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Dots extends Component
{
    public function render(): View
    {
        return view('pjutils::table.cells.actions.dots');
    }
}
