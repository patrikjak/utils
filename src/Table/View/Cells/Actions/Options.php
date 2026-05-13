<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Table\View\Cells\Actions;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\ValueObjects\Cells\Actions\Item;

class Options extends Component
{
    /**
     * @param Collection<int, Item> $actions
     */
    public function __construct(public Collection $actions)
    {
    }

    public function render(): View
    {
        return view('pjutils::table.cells.actions.options');
    }
}
