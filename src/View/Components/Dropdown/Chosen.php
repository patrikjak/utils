<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components\Dropdown;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Chosen extends Component
{
    public function __construct(public string $value, public string $label)
    {
    }

    public function render(): View
    {
        return view('pjutils::components.dropdown.chosen');
    }
}
