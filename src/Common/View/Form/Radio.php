<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\View\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Radio extends Component
{
    public string $wrapperClass;

    public function __construct(
        public readonly string $label,
        public readonly bool $required = false,
        public readonly ?string $value = null,
    ) {
    }

    public function render(): View
    {
        $this->wrapperClass = $this->resolveWrapperClass();

        return view('pjutils::components.form.radio');
    }

    private function resolveWrapperClass(): string
    {
        $classes = ['pj-radio'];

        if ($this->required) {
            $classes[] = 'required';
        }

        return implode(' ', $classes);
    }
}
