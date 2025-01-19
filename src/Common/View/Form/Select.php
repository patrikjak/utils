<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\View\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Select extends Component
{
    public string $wrapperClass;

    /**
     * @param Collection<string, string> $options
     * @param null|string|array<string> $value
     */
    public function __construct(
        public readonly iterable $options,
        public readonly ?string $label = null,
        public readonly ?string $error = null,
        public readonly null|string|array $value = null,
        public readonly bool $required = false,
    ) {
    }

    public function render(): View
    {
        $this->wrapperClass = $this->resolveWrapperClass();

        return view('pjutils::components.form.select');
    }

    private function resolveWrapperClass(): string
    {
        $classes = ['pj-select'];

        if ($this->required) {
            $classes[] = 'required';
        }

        if ($this->error) {
            $classes[] = 'error';
        }

        return implode(' ', $classes);
    }
}
