<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components\Dropdown;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Item extends Component
{
    public readonly string $itemClass;

    public function __construct(public string $value, public string $label, public bool $selected = false)
    {
        $this->itemClass = $this->resolveClasses();
    }

    public function render(): View
    {
        return view('pjutils::components.dropdown.item');
    }

    private function resolveClasses(): string
    {
        return implode(' ', ['be-dropdown-item']);
    }
}
