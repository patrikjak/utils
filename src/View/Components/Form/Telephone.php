<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Telephone extends Component
{
    public function __construct(public string $pattern = '^\+421\d{9}$')
    {
    }

    public function render(): View
    {
        return view('pjutils::components.form.telephone');
    }
}
