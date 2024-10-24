<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Dropdown extends Component
{
    /**
     * @param iterable<string, string> $items
     */
    public function __construct(
        public iterable $items,
        public null|string|int $selected = null,
        public ?string $label = null,
    ) {
        if ($selected === null) {
            $this->selected = array_key_first($items);
        }
    }

    public function render(): View
    {
        return view('pjutils::components.dropdown');
    }
}
