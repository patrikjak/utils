<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Repeater extends Component
{
    public function __construct(
        public readonly int $min = 1,
        public readonly ?int $max = null,
        public readonly string $addLabel = 'Add',
        public readonly string $removeLabel = 'Remove',
    ) {
    }

    public function render(): View
    {
        return view('pjutils::components.form.repeater');
    }
}
