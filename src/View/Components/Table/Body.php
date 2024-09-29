<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components\Table;

use Illuminate\Contracts\View\View;
use Patrikjak\Utils\View\Components\Table;

class Body extends Table
{
    public function render(): View
    {
        return view('pjutils::components.table.body');
    }

    public function emptyBody(): bool
    {
        return $this->table->data === [];
    }
}
