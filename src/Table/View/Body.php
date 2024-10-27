<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Table\View;

use Illuminate\Contracts\View\View;

class Body extends Table
{
    public function render(): View
    {
        return view('pjutils::table.body');
    }

    public function emptyBody(): bool
    {
        return $this->table->data === [];
    }
}
