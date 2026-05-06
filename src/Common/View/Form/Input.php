<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Common\Icon;

class Input extends Component
{
    public string $wrapperClass;

    public ?Icon $icon = null;

    public function __construct(
        public readonly ?string $label = null,
        public readonly ?string $error = null,
        public readonly bool $required = false,
        ?Icon $icon = null,
    ) {
        $this->icon = $icon;
    }

    public function render(): View
    {
        if (isset($this->error)) {
            $this->icon = Icon::heroicon('heroicon-o-exclamation-circle');
        }

        $this->wrapperClass = $this->resolveWrapperClass();

        return view('pjutils::components.form.input');
    }

    public function resolveWrapperClass(): string
    {
        $classes = ['pj-classic'];

        if ($this->icon !== null) {
            $classes[] = 'iconic';
        }

        if (isset($this->error)) {
            $classes[] = 'error';
        }

        if ($this->required) {
            $classes[] = 'required';
        }

        return implode(' ', $classes);
    }
}
