<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tab extends Component
{
    public function __construct(
        public readonly string $label,
        public readonly bool $active = false,
    ) {
    }

    public function render(): View
    {
        return view('pjutils::components.tab');
    }
}
