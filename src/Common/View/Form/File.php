<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\View\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class File extends Component
{
    public string $wrapperClass;

    public function __construct(
        public readonly ?string $label = null,
        public readonly ?string $error = null,
        public readonly bool $required = false,
    ) {
    }

    public function render(): View
    {
        $this->wrapperClass = $this->resolveWrapperClass();

        return view('pjutils::components.form.file');
    }

    private function resolveWrapperClass(): string
    {
        $classes = ['pj-file'];

        if ($this->error) {
            $classes[] = 'error';
        }

        if ($this->required) {
            $classes[] = 'required';
        }

        return implode(' ', $classes);
    }
}
