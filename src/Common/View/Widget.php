<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Widget extends Component
{
    public string $classes;

    public function __construct(
        public ?string $title = null,
        public ?string $subtitle = null,
        public string $size = 'full',
        public ?string $height = null,
        public int $colSpan = 1,
    ) {
        $this->classes = $this->getClasses();
    }

    public function render(): View
    {
        return view('pjutils::components.widget');
    }

    private function getClasses(): string
    {
        $classes = ['pj-widget'];

        if (in_array($this->size, ['xs', 'sm', 'md', 'full'], true)) {
            $classes[] = $this->size;
        }

        if ($this->height !== null && in_array($this->height, ['sm', 'md', 'lg', 'full'], true)) {
            $classes[] = "h-{$this->height}";
        }

        if ($this->colSpan > 1 && $this->colSpan <= 4) {
            $classes[] = "col-span-{$this->colSpan}";
        }

        return implode(' ', $classes);
    }
}
