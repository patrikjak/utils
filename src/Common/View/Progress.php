<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Common\Enums\ProgressType;

class Progress extends Component
{
    public readonly string $classes;

    public readonly int $clampedValue;

    public function __construct(
        public readonly int $value = 0,
        public readonly ProgressType $type = ProgressType::DEFAULT,
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

        if ($this->type !== ProgressType::DEFAULT) {
            $classes[] = $this->type->value;
        }

        return implode(' ', $classes);
    }
}
