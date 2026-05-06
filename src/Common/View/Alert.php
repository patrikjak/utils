<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Common\Enums\AlertType;

class Alert extends Component
{
    public string $classes;

    public function __construct(
        public AlertType $type = AlertType::INFO,
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

        if ($this->type !== AlertType::INFO) {
            $classes[] = $this->type->value;
        }

        if ($this->title !== null) {
            $classes[] = 'has-title';
        }

        return implode(' ', $classes);
    }
}
