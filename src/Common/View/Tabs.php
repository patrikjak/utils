<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Tabs extends Component
{
    public function render(): View
    {
        return view('pjutils::components.tabs');
    }
}
