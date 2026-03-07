<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Badge extends Component
{
    public string $classes;

    public function __construct(
        public string $type = 'default',
    ) {
        $this->classes = $this->getClasses();
    }

    public function render(): View
    {
        return view('pjutils::components.badge');
    }

    private function getClasses(): string
    {
        $classes = ['pj-badge'];

        if (in_array($this->type, ['success', 'danger', 'warning', 'info'], true)) {
            $classes[] = $this->type;
        }

        return implode(' ', $classes);
    }
}
