<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Arrow extends Component
{
    public function __construct(public string $direction = 'down')
    {
    }

    public function render(): View
    {
        return view('pjutils::components.arrow');
    }
}
