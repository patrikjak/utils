<?php

declare(strict_types = 1);

namespace Patrikjak\Utils\Common\View;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Patrikjak\Utils\Common\Enums\Icon;

class SummaryCard extends Component
{
    public function __construct(
        public string $title,
        public ?string $status = null,
        public ?string $statusLabel = null,
        public ?Icon $icon = null,
    ) {
    }

    public function render(): View
    {
        return view('pjutils::components.summary-card');
    }
}
