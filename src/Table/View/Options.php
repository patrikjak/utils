<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Table\Dto\Table;

class Options extends Component
{
    public function __construct(public Table $table)
    {
    }

    public function render(): View
    {
        return $this->view('pjutils::table.options');
    }
}
