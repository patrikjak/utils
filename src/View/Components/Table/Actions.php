<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components\Table;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Services\Actions\ActionInterface;

class Actions extends Component
{
    /**
     * @param array<ActionInterface> $actions
     */
    public function __construct(public array $actions)
    {
    }

    public function render(): View
    {
        return view('pjutils::components.table.actions');
    }
}
