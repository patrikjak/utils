<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View\Cells\Actions;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Cells\Actions\Item;

class Options extends Component
{
    /**
     * @param array<Item> $actions
     */
    public function __construct(public array $actions)
    {
    }

    public function render(): View
    {
        return view('pjutils::table.cells.actions.options');
    }
}
