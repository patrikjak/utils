<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Accordion extends Component
{
    public function __construct(
        public readonly string $title,
        public readonly bool $open = false,
    ) {
    }

    public function render(): View
    {
        return view('pjutils::components.accordion');
    }
}
