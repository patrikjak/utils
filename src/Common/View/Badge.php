<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Common\Enums\BadgeType;

final class Badge extends Component
{
    public string $classes;

    public function __construct(
        public BadgeType $type = BadgeType::DEFAULT,
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

        if ($this->type !== BadgeType::DEFAULT) {
            $classes[] = $this->type->value;
        }

        return implode(' ', $classes);
    }
}
