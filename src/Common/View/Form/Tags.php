<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tags extends Component
{
    public readonly string $wrapperClass;

    /**
     * @param array<int, string> $value
     */
    public function __construct(
        public readonly ?string $label = null,
        public readonly array $value = [],
        public readonly ?string $placeholder = null,
        public readonly ?string $error = null,
    ) {
        $this->wrapperClass = $this->resolveWrapperClass();
    }

    public function render(): View
    {
        return view('pjutils::components.form.tags');
    }

    private function resolveWrapperClass(): string
    {
        $classes = ['pj-tags'];

        if ($this->error !== null) {
            $classes[] = 'error';
        }

        return implode(' ', $classes);
    }
}
