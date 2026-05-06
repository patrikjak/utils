<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AccordionGroup extends Component
{
    public function render(): View
    {
        return view('pjutils::components.accordion-group');
    }
}
