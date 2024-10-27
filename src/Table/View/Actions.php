<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Action;

class Actions extends Component
{
    /**
     * @param array<Action> $actions
     */
    public function __construct(public array $actions)
    {
    }

    public function render(): View
    {
        return view('pjutils::table.actions');
    }
}
