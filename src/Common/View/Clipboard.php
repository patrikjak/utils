<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Clipboard extends Component
{
    public function __construct(
        public readonly string $value,
        public readonly ?string $label = null,
    ) {
    }

    public function render(): View
    {
        return view('pjutils::components.clipboard');
    }
}
