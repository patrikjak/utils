<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WidgetGrid extends Component
{
    public string $classes;

    public function __construct(
        public int $cols = 2,
        public string $gap = 'md',
    ) {
        $this->classes = $this->getClasses();
    }

    public function render(): View
    {
        return view('pjutils::components.widget-grid');
    }

    private function getClasses(): string
    {
        $classes = ['pj-widget-grid'];

        if ($this->cols >= 1 && $this->cols <= 4) {
            $classes[] = "cols-{$this->cols}";
        }

        if (in_array($this->gap, ['sm', 'lg'], true)) {
            $classes[] = "gap-{$this->gap}";
        }

        return implode(' ', $classes);
    }
}
