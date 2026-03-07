<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Divider extends Component
{
    public function __construct(
        public ?string $label = null,
    ) {
    }

    public function render(): View
    {
        return view('pjutils::components.divider');
    }
}
