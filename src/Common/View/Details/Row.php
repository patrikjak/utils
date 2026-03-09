<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View\Details;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Row extends Component
{
    public function __construct(
        public string $label,
    ) {
    }

    public function render(): View
    {
        return view('pjutils::components.details.row');
    }
}
