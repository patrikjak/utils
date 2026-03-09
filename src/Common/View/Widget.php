<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Common\Enums\WidgetHeight;
use Patrikjak\Utils\Common\Enums\WidgetSize;

final class Widget extends Component
{
    public string $classes;

    public function __construct(
        public ?string $title = null,
        public ?string $subtitle = null,
        public WidgetSize $size = WidgetSize::FULL,
        public ?WidgetHeight $height = null,
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
        $classes = ['pj-widget', $this->size->value];

        if ($this->height !== null) {
            $classes[] = "h-{$this->height->value}";
        }

        if ($this->colSpan > 1 && $this->colSpan <= 4) {
            $classes[] = "col-span-{$this->colSpan}";
        }

        return implode(' ', $classes);
    }
}
