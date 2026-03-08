<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Combobox extends Component
{
    public readonly string $wrapperClass;

    public readonly string $displayValue;

    /**
     * @param array<string|int, string> $options
     */
    public function __construct(
        public readonly ?string $label = null,
        public readonly array $options = [],
        public readonly string|int|null $value = null,
        public readonly ?string $placeholder = null,
        public readonly ?string $error = null,
    ) {
        $this->wrapperClass = $this->resolveWrapperClass();
        $this->displayValue = $this->resolveDisplayValue();
    }

    public function render(): View
    {
        return view('pjutils::components.form.combobox');
    }

    private function resolveWrapperClass(): string
    {
        $classes = ['pj-combobox'];

        if ($this->error !== null) {
            $classes[] = 'error';
        }

        return implode(' ', $classes);
    }

    private function resolveDisplayValue(): string
    {
        if ($this->value === null) {
            return '';
        }

        return $this->options[$this->value] ?? '';
    }
}
