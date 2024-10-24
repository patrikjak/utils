<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Select extends Component
{
    public readonly string $wrapperClass;

    /**
     * @param iterable<array<string, string>|Collection<string, string>> $options
     * @param null|string|array<string> $value
     */
    public function __construct(
        public readonly string $name,
        public readonly iterable $options,
        public readonly ?string $label = null,
        public readonly ?string $error = null,
        public readonly null|string|array $value = null,
        public readonly bool $required = false,
    ) {
        $this->wrapperClass = $this->resolveWrapperClass();
    }

    public function render(): View
    {
        return view('pjutils::components.form.select');
    }

    private function resolveWrapperClass(): string
    {
        $classes = ['pj-select'];

        if ($this->required) {
            $classes[] = 'required';
        }

        return implode(' ', $classes);
    }
}
