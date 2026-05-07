<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Details extends Component
{
    /**
     * @param array<string, string> $rows
     */
    public function __construct(
        public array $rows = [],
    ) {
    }

    public function render(): View
    {
        return view('pjutils::components.details');
    }
}
