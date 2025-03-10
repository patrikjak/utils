<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    public string $classes;

    public function __construct(
        public ?string $href = null,
        public bool $loading = false,
        public bool $bordered = false,
        public bool $texted = false,
    ) {
    }

    public function render(): View
    {
        $this->classes = $this->getClasses();

        return view('pjutils::components.button');
    }

    private function getClasses(): string
    {
        $classes = ['pj-btn'];

        if ($this->texted) {
            $classes[] = 'texted';
        }

        if ($this->bordered) {
            $classes[] = 'bordered';
        }

        if ($this->loading) {
            $classes[] = 'loading';
        }

        if ($this->href) {
            $classes[] = 'button-like';
        }

        return implode(' ', $classes);
    }
}
