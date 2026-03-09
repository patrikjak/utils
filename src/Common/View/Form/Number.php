<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Number extends Component
{
    public readonly string $wrapperClass;

    public function __construct(
        public readonly ?string $label = null,
        public readonly int|float $value = 0,
        public readonly int|float|null $min = null,
        public readonly int|float|null $max = null,
        public readonly int|float $step = 1,
        public readonly ?string $error = null,
    ) {
        $this->wrapperClass = $this->resolveWrapperClass();
    }

    public function render(): View
    {
        return view('pjutils::components.form.number');
    }

    private function resolveWrapperClass(): string
    {
        $classes = ['pj-number'];

        if ($this->error !== null) {
            $classes[] = 'error';
        }

        return implode(' ', $classes);
    }
}
