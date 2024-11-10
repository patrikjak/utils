<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Common\Enums\Type;

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
        $classes = ['pj-btn'];

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