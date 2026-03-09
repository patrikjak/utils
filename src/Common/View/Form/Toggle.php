<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Toggle extends Component
{
    public function __construct(
        public readonly ?string $label = null,
        public readonly bool $checked = false,
        public readonly bool $disabled = false,
    ) {
    }

    public function render(): View
    {
        return view('pjutils::components.form.toggle');
    }
}
