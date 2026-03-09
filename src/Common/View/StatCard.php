<?php

declare(strict_types=1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class StatCard extends Component
{
    public string $trendDirection;

    public function __construct(
        public string $label,
        public string|int $value,
        public ?string $trend = null,
    ) {
        $this->trendDirection = $this->detectTrendDirection();
    }

    public function render(): View
    {
        return view('pjutils::components.stat-card');
    }

    private function detectTrendDirection(): string
    {
        if ($this->trend === null) {
            return 'neutral';
        }

        if (str_starts_with($this->trend, '+')) {
            return 'up';
        }

        if (str_starts_with($this->trend, '-')) {
            return 'down';
        }

        return 'neutral';
    }
}
