<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Textarea extends Component
{
    public readonly string $wrapperClass;

    public function __construct(
        public readonly string $name,
        public readonly ?string $label = null,
        public readonly bool $required = false,
        public readonly ?string $value = null,
    ) {
        $this->wrapperClass = $this->resolveWrapperClass();
    }

    public function render(): View
    {
        return view('pjutils::components.form.textarea');
    }

    private function resolveWrapperClass(): string
    {
        $classes = ['pj-textarea'];

        if ($this->required) {
            $classes[] = 'required';
        }

        return implode(' ', $classes);
    }
}
