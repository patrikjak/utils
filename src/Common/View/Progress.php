<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Progress extends Component
{
    public readonly string $classes;

    public readonly int $clampedValue;

    public function __construct(
        public readonly int $value = 0,
        public readonly string $type = 'default',
        public readonly ?string $label = null,
        public readonly bool $showLabel = true,
    ) {
        $this->clampedValue = max(0, min(100, $this->value));
        $this->classes = $this->resolveClasses();
    }

    public function render(): View
    {
        return view('pjutils::components.progress');
    }

    private function resolveClasses(): string
    {
        $classes = ['pj-progress'];

        if (in_array($this->type, ['success', 'danger', 'warning'], true)) {
            $classes[] = $this->type;
        }

        return implode(' ', $classes);
    }
}
