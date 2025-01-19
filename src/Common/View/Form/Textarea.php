<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\View\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Textarea extends Component
{
    public string $wrapperClass;

    public function __construct(
        public readonly ?string $label = null,
        public readonly bool $required = false,
        public readonly ?string $value = null,
    ) {
    }

    public function render(): View
    {
        $this->wrapperClass = $this->resolveWrapperClass();

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
