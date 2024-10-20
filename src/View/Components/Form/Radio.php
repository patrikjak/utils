<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Radio extends Component
{
    public readonly string $wrapperClass;

    public function __construct(
        public readonly string $name,
        public readonly string $label,
        public readonly string $id,
        public readonly bool $checked = false,
        public readonly bool $disabled = false,
        public readonly bool $required = false,
        public readonly ?string $value = null,
    ) {
        $this->wrapperClass = $this->resolveWrapperClass();
    }

    public function render(): View
    {
        return view('pjutils::components.form.radio');
    }

    private function resolveWrapperClass(): string
    {
        $classes = ['be-radio'];

        if ($this->required) {
            $classes[] = 'required';
        }

        return implode(' ', $classes);
    }
}
