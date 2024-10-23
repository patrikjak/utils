<?php

namespace Patrikjak\Utils\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\View\Type;

class Button extends Component
{
    public readonly string $classes;

    public function __construct(
        public Type $type = Type::INFO,
        public ?string $href = null,
        public bool $loading = false,
        public bool $bordered = false,
        public bool $texted = false,
    ) {
        $this->classes = $this->getClasses();
    }

    public function render(): View
    {
        return view('pjutils::components.button');
    }

    private function getClasses(): string
    {
        $classes = ['be-btn'];

        $typeClass = match (true) {
            $this->texted === true => sprintf('%s-%s', $this->type->value, 'texted'),
            $this->bordered === true => sprintf('%s-%s', $this->type->value, 'bordered'),
            default => $this->type->value,
        };

        $classes[] = $typeClass;

        if ($this->loading) {
            $classes[] = 'loading';
        }

        if ($this->href) {
            $classes[] = 'button-like';
        }

        return implode(' ', $classes);
    }
}
