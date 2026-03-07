<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public string $classes;

    public function __construct(
        public string $type = 'info',
        public ?string $title = null,
        public bool $dismissible = true,
    ) {
        $this->classes = $this->getClasses();
    }

    public function render(): View
    {
        return view('pjutils::components.alert');
    }

    private function getClasses(): string
    {
        $classes = ['pj-alert'];

        if (in_array($this->type, ['success', 'danger', 'warning'], true)) {
            $classes[] = $this->type;
        }

        return implode(' ', $classes);
    }
}
